# Padrão Abstract Factory

## Definição

O padrão Abstract Factory é um design pattern criacional que permite **criar famílias de objetos relacionados sem especificar suas classes concretas**. Ele fornece uma interface para criar famílias de objetos que trabalham juntos, garantindo que os produtos criados sejam compatíveis entre si.

## Quando Usar

- **Famílias de produtos**: Quando você precisa criar grupos de objetos relacionados
- **Independência de implementação**: O sistema deve ser independente de como os produtos são criados
- **Consistência entre produtos**: Garantir que produtos de uma família sejam usados juntos
- **Múltiplas plataformas**: Quando o sistema precisa funcionar com diferentes "sabores" de produtos

## Problemática

### Dependência de Classes Específicas
Quando o código precisa criar objetos relacionados mas fica acoplado a implementações específicas:

```php
class OrderProcessor
{
    public function processOrder(string $type, float $value, DateTimeImmutable $date): void
    {
        // ❌ Acoplamento direto - difícil de manter e estender
        if ($type === 'product') {
            $sale = new ProductSale($date, $value);
            $tax = new Icms(); // Imposto específico para produtos
        } elseif ($type === 'service') {
            $sale = new ServiceSale($date, 'Consultoria');
            $tax = new Iss(); // Imposto específico para serviços
        }

        // E se precisarmos adicionar um novo tipo?
        // E se a regra de qual imposto usar mudar?
        // Teríamos que modificar esta classe...
        
        $taxValue = $tax->calculate($budget);
        // Processar venda...
    }
}
```

### Problemas da Abordagem Direta
- **Violação do Open/Closed Principle**: Adicionar novos tipos requer modificação do código
- **Inconsistência**: Nada garante que Sale e Tax sejam compatíveis
- **Duplicação**: Lógica de criação espalhada por diferentes lugares
- **Complexidade**: Código cliente precisa conhecer todas as combinações válidas

## Solução

### 1. Produtos Abstratos
Definição das interfaces/classes base para cada tipo de produto:

```php
// Produto abstrato: Sale
abstract class Sale
{
    public function __construct(public readonly DateTimeImmutable $saleDate) {}
    
    abstract public function getType(): string;
    abstract public function getValue(): float;
}

// Interface para impostos
interface TaxInterface
{
    public function calculate(Budget $budget): float;
    public function getType(): string;
}
```

### 2. Produtos Concretos
Implementações específicas para cada família:

```php
// Família "Produto"
class ProductSale extends Sale
{
    public function __construct(
        DateTimeImmutable $saleDate,
        public readonly float $productValue
    ) {
        parent::__construct($saleDate);
    }

    public function getType(): string
    {
        return 'product';
    }

    public function getValue(): float
    {
        return $this->productValue;
    }
}

class Icms implements TaxInterface
{
    public function calculate(Budget $budget): float
    {
        return $budget->value() * 0.18; // 18% para produtos
    }

    public function getType(): string
    {
        return 'ICMS';
    }
}

// Família "Serviço"
class ServiceSale extends Sale
{
    public function __construct(
        DateTimeImmutable $saleDate,
        public readonly string $serviceName
    ) {
        parent::__construct($saleDate);
    }

    public function getType(): string
    {
        return 'service';
    }

    public function getValue(): float
    {
        // Valor pode ser calculado de forma diferente para serviços
        return 1000.0; // Simplificado para exemplo
    }
}

class Iss implements TaxInterface
{
    public function calculate(Budget $budget): float
    {
        return $budget->value() * 0.05; // 5% para serviços
    }

    public function getType(): string
    {
        return 'ISS';
    }
}
```

### 3. Abstract Factory Interface
Interface que define métodos para criar cada produto da família:

```php
interface SaleFactory
{
    public function make(): Sale;
    public function getTax(): TaxInterface;
}
```

### 4. Concrete Factories
Implementações que criam produtos compatíveis de uma família específica:

```php
readonly class ProductSaleFactory implements SaleFactory
{
    public function __construct(
        private DateTimeImmutable $saleDate,
        private float $productValue
    ) {}

    public function make(): Sale
    {
        return new ProductSale($this->saleDate, $this->productValue);
    }

    public function getTax(): TaxInterface
    {
        return new Icms(); // ✅ Sempre retorna o imposto correto para produtos
    }
}

readonly class ServiceSaleFactory implements SaleFactory
{
    public function __construct(
        private DateTimeImmutable $saleDate,
        private string $serviceName
    ) {}

    public function make(): Sale
    {
        return new ServiceSale($this->saleDate, $this->serviceName);
    }

    public function getTax(): TaxInterface
    {
        return new Iss(); // ✅ Sempre retorna o imposto correto para serviços
    }
}
```

## Utilização da Solução

### Uso Básico
```php
class OrderProcessor
{
    public function processOrder(SaleFactory $factory, Budget $budget): void
    {
        // ✅ Desacoplado - trabalha com qualquer factory
        $sale = $factory->make();
        $tax = $factory->getTax();
        
        // ✅ Garantia de compatibilidade entre sale e tax
        $taxValue = $tax->calculate($budget);
        
        echo "Processando {$sale->getType()}: {$sale->getValue()}\n";
        echo "Imposto {$tax->getType()}: R$ {$taxValue}\n";
    }
}

// Uso flexível
$processor = new OrderProcessor();

// Processar venda de produto
$productFactory = new ProductSaleFactory(
    new DateTimeImmutable(),
    1500.00
);
$processor->processOrder($productFactory, $budget);

// Processar venda de serviço
$serviceFactory = new ServiceSaleFactory(
    new DateTimeImmutable(),
    'Desenvolvimento de Software'
);
$processor->processOrder($serviceFactory, $budget);
```

### Factory Provider
Para facilitar a seleção da factory apropriada:

```php
class SaleFactoryProvider
{
    public static function getFactory(
        string $type, 
        DateTimeImmutable $date, 
        mixed $value
    ): SaleFactory {
        return match($type) {
            'product' => new ProductSaleFactory($date, (float) $value),
            'service' => new ServiceSaleFactory($date, (string) $value),
            default => throw new InvalidArgumentException("Unknown sale type: $type")
        };
    }
}

// Uso simplificado
$factory = SaleFactoryProvider::getFactory('product', new DateTimeImmutable(), 1500.00);
$processor->processOrder($factory, $budget);
```

## Exemplo Avançado: Sistema Multi-Regional

```php
// Diferentes famílias para diferentes regiões
interface RegionalSaleFactory extends SaleFactory
{
    public function getPaymentProcessor(): PaymentProcessorInterface;
    public function getShippingCalculator(): ShippingCalculatorInterface;
}

class BrazilSaleFactory implements RegionalSaleFactory
{
    public function make(): Sale
    {
        return new BrazilianSale($this->saleDate, $this->value);
    }

    public function getTax(): TaxInterface
    {
        return new BrazilianTax(); // ICMS, IPI, etc.
    }

    public function getPaymentProcessor(): PaymentProcessorInterface
    {
        return new PixPaymentProcessor();
    }

    public function getShippingCalculator(): ShippingCalculatorInterface
    {
        return new CorreiosShipping();
    }
}

class USASaleFactory implements RegionalSaleFactory
{
    public function make(): Sale
    {
        return new USASale($this->saleDate, $this->value);
    }

    public function getTax(): TaxInterface
    {
        return new SalesTax(); // Sales tax americano
    }

    public function getPaymentProcessor(): PaymentProcessorInterface
    {
        return new StripePaymentProcessor();
    }

    public function getShippingCalculator(): ShippingCalculatorInterface
    {
        return new FedExShipping();
    }
}
```

## Características do Padrão

### ✅ Vantagens
- **Consistência**: Garante que produtos relacionados sejam usados juntos
- **Isolamento**: Separa código cliente das classes concretas
- **Facilita mudanças**: Trocar toda uma família é simples
- **Extensibilidade**: Adicionar novas famílias não afeta código existente
- **Reutilização**: Famílias podem ser reutilizadas em diferentes contextos

### ⚠️ Desvantagens
- **Complexidade**: Introduz muitas interfaces e classes
- **Rigidez**: Adicionar novos tipos de produtos requer mudanças em todas as factories
- **Overhead**: Pode ser excessivo para casos simples

## Implementação Completa no Projeto

### Estrutura Organizada
```php
<?php

declare(strict_types=1);

namespace Tiagolopes\DesignPatterns\Entity\Sale;

use DateTimeImmutable;
use Tiagolopes\DesignPatterns\Entity\Tax\TaxInterface;

// Abstract Factory Interface
interface SaleFactory
{
    public function make(): Sale;
    public function getTax(): TaxInterface;
}

// Abstract Product
abstract class Sale
{
    public function __construct(public readonly DateTimeImmutable $saleDate) {}
}

// Concrete Products e Factories...
```

## Comparação com Factory Method

| Aspecto | Factory Method | Abstract Factory |
|---------|----------------|------------------|
| **Foco** | Um produto | Família de produtos |
| **Complexidade** | Menor | Maior |
| **Uso** | Variações de um tipo | Produtos relacionados |
| **Extensibilidade** | Por herança | Por composição |

## Quando NÃO Usar

- **Famílias simples**: Quando você tem apenas um produto
- **Raramente muda**: Se as famílias são estáveis e raramente mudam
- **Performance crítica**: Overhead de abstrações pode ser desnecessário

O Abstract Factory é ideal quando você precisa garantir que conjuntos de objetos relacionados sejam criados de forma consistente e compatível, especialmente em sistemas que precisam suportar múltiplas variações ou plataformas.
