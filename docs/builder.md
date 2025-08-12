# Padrão Builder

## Definição

O padrão Builder é um design pattern criacional que permite **construir objetos complexos passo a passo**. Ele é especialmente útil quando você precisa criar objetos com muitos parâmetros opcionais ou quando o processo de construção precisa permitir diferentes representações do objeto final.

## Quando Usar

- **Objetos com muitos parâmetros**: Evitar construtores telescópicos
- **Construção complexa**: Quando o objeto requer múltiplas etapas para ser criado
- **Configurações opcionais**: Muitos parâmetros são opcionais
- **Diferentes representações**: O mesmo processo pode criar diferentes tipos do objeto

## Problemática

### Construtor Telescópico
Construtor com muitos parâmetros torna o código difícil de ler e manter:

```php
readonly class Invoice
{
    public function __construct(
        public string $cnpj,
        public string $companyName,
        public array $items,
        public ?string $observacoes,
        public DateTimeImmutable $issueDate,
        public float $taxValue
    ) {
    }
}
```

### Problemas na Utilização
```php
// ❌ Difícil de ler e propenso a erros
$invoice = new Invoice(
    cnpj: '12.345.678/0001-90',
    companyName: 'Empresa XYZ Ltda',
    items: [$budget1, $budget2],
    observacoes: null, // Pode ser esquecido
    issueDate: new DateTimeImmutable(),
    taxValue: 0.0 // Precisa ser calculado manualmente
);

// ❌ Ordem dos parâmetros pode confundir
$invoice2 = new Invoice(
    'Empresa ABC', // Ops! Inverteu a ordem
    '98.765.432/0001-10',
    [],
    'Observação importante',
    new DateTimeImmutable(),
    150.50
);
```

## Solução

### 1. Builder Abstrato Base
Criação de builder abstrato com métodos fluentes para preenchimento das informações:

```php
abstract class InvoiceBuilder
{
    protected Invoice $invoice;
    
    public function __construct()
    {
        $this->invoice = new Invoice;
    }

    public function withCompany(string $company, string $cnpj): self
    {
        $this->invoice->companyName = $company;
        $this->invoice->cnpj = $cnpj;
        return $this;
    }

    public function withItem(Budget $budget): self
    {
        $this->invoice->items[] = $budget;
        return $this;
    }

    public function withNote(string $note): self
    {
        $this->invoice->note = $note;
        return $this;
    }

    public function withIssueDate(DateTimeImmutable $date): self
    {
        $this->invoice->issueDate = $date;
        return $this;
    }

    // Método abstrato para permitir diferentes tipos de processamento
    abstract public function build(): Invoice;
}
```

### 2. Builder Específico para Serviços
```php
class ServiceInvoiceBuilder extends InvoiceBuilder
{
    public function build(): Invoice
    {
        // Cálculo específico para serviços (30% de imposto)
        $total = $this->invoice->getTotalValue();
        $this->invoice->taxValue = $total * 0.30;

        return $this->invoice;
    }
}
```

### 3. Builder Específico para Produtos
```php
class ProductInvoiceBuilder extends InvoiceBuilder
{
    public function build(): Invoice
    {
        // Cálculo específico para produtos (18% de imposto)
        $total = $this->invoice->getTotalValue();
        $this->invoice->taxValue = $total * 0.18;

        return $this->invoice;
    }
}
```

## Utilização Melhorada

### Interface Fluente e Clara
```php
$budget = new Budget(value: 1000, itemsCount: 6);

// ✅ Legível e flexível
$serviceInvoice = new ServiceInvoiceBuilder()
    ->withCompany(company: 'Tech Solutions Ltda', cnpj: '12.345.678/0001-90')
    ->withItem($budget)
    ->withNote('Desenvolvimento de sistema web')
    ->build(); // Taxa automática: 30%

$productInvoice = new ProductInvoiceBuilder()
    ->withCompany(company: 'Commerce Corp', cnpj: '98.765.432/0001-10')
    ->withItem($budget)
    ->withNote('Venda de equipamentos')
    ->build(); // Taxa automática: 18%
```

### Construção Condicional
```php
$builder = new ServiceInvoiceBuilder()
    ->withCompany('MinhaEmpresa', '12.345.678/0001-90')
    ->withItem($budget);

// Adicionar nota apenas se necessário
if ($needsNote) {
    $builder->withNote('Observação especial');
}

// Adicionar mais itens conforme necessário
foreach ($additionalBudgets as $extraBudget) {
    $builder->withItem($extraBudget);
}

$invoice = $builder->build();
```

## Características do Padrão

### ✅ Vantagens
- **Legibilidade**: Interface fluente torna o código autodocumentado
- **Flexibilidade**: Permite construção passo a passo com parâmetros opcionais
- **Reutilização**: O mesmo processo pode criar diferentes representações
- **Validação**: Pode validar o objeto antes da construção final
- **Imutabilidade**: Pode garantir que o objeto final seja imutável

### ⚠️ Desvantagens
- **Complexidade adicional**: Mais código para casos simples
- **Performance**: Overhead de criar o builder para objetos simples

## Implementação Completa no Projeto

### Classe Invoice com Suporte ao Padrão
```php
<?php

declare(strict_types=1);

namespace Tiagolopes\DesignPatterns\Entity\Invoice;

use DateTimeImmutable;
use Tiagolopes\DesignPatterns\Entity\Budget\Budget;

class Invoice
{
    public string $cnpj;
    public string $companyName;
    public array $items;
    public ?string $note;
    public DateTimeImmutable $issueDate;
    public float $taxValue;

    public function __construct()
    {
        $this->items = [];
        $this->issueDate = new DateTimeImmutable;
    }

    public function getTotalValue(): float
    {
        if (empty($this->items)) {
            return 0;
        }

        return array_reduce(
            array: $this->items,
            callback: fn ($total, Budget $item) => $total + $item->value()
        );
    }

    public function __clone(): void
    {
        $this->issueDate = new DateTimeImmutable;
    }
}
```

## Variações do Padrão

### Builder com Director
Para casos mais complexos, você pode usar um Director que conhece as etapas específicas:

```php
class InvoiceDirector
{
    public function createServiceInvoice(
        string $company, 
        string $cnpj, 
        array $budgets
    ): Invoice {
        $builder = new ServiceInvoiceBuilder();
        
        $builder->withCompany($company, $cnpj);
        
        foreach ($budgets as $budget) {
            $builder->withItem($budget);
        }
        
        return $builder->build();
    }
}
```

O padrão Builder é uma excelente solução para construir objetos complexos de forma clara e flexível, especialmente quando combinado com interfaces fluentes que tornam o código mais expressivo e fácil de entender.
