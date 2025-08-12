# Padrão Prototype

## Definição

O padrão Prototype é um design pattern criacional que permite **copiar objetos existentes sem depender de suas classes concretas**. Ao invés de criar novos objetos do zero, você clona um protótipo existente, economizando tempo e recursos quando o processo de criação é custoso.

## Quando Usar

- **Objetos custosos de criar**: Quando a inicialização é cara (consultas ao banco, cálculos complexos)
- **Configurações similares**: Objetos com pequenas variações de um modelo base
- **Evitar subclasses**: Quando criar subclasses apenas para diferentes configurações seria excessivo
- **Estados complexos**: Objetos com estado interno complexo difícil de recriar

## Problemática

### Criação Repetitiva e Custosa
Objetos com muitas configurações iguais, mudando apenas algumas informações específicas:

```php
// ❌ Repetição desnecessária de código e configurações
$invoice1 = new ServiceInvoiceBuilder()
    ->withItem($budget)
    ->withNote('Desenvolvimento de sistema')
    ->withCompany(company: 'Tech Solutions', cnpj: '12.345.678/0001-90')
    ->build();

$invoice2 = new ServiceInvoiceBuilder()
    ->withItem($budget)  // Repetido
    ->withNote('Desenvolvimento de sistema')  // Repetido
    ->withCompany(company: 'Tech Solutions 2', cnpj: '98.765.432/0001-10')  // Só mudou
    ->build();

$invoice3 = new ServiceInvoiceBuilder()
    ->withItem($budget)  // Repetido
    ->withNote('Desenvolvimento de sistema')  // Repetido
    ->withCompany(company: 'Tech Solutions 3', cnpj: '11.222.333/0001-44')  // Só mudou
    ->build();
```

### Problemas da Abordagem Tradicional
- **Performance**: Reconstrução completa do objeto a cada vez
- **Duplicação**: Repetição de configurações idênticas
- **Manutenção**: Mudanças no processo base afetam múltiplos pontos
- **Complexidade**: Objetos com estado interno complexo são difíceis de recriar

## Solução

### 1. Utilizando `clone` Nativo do PHP
O PHP oferece a palavra-chave `clone` que facilita a implementação do padrão:

```php
// ✅ Criação do protótipo base
$prototypeInvoice = new ServiceInvoiceBuilder()
    ->withItem($budget)
    ->withNote('Desenvolvimento de sistema')
    ->withCompany(company: 'Tech Solutions Base', cnpj: '00.000.000/0001-00')
    ->build();

// ✅ Clonagem e personalização rápida
$invoice1 = clone $prototypeInvoice;
$invoice1->companyName = 'Tech Solutions 1';
$invoice1->cnpj = '12.345.678/0001-90';

$invoice2 = clone $prototypeInvoice;
$invoice2->companyName = 'Tech Solutions 2';
$invoice2->cnpj = '98.765.432/0001-10';

$invoice3 = clone $prototypeInvoice;
$invoice3->companyName = 'Tech Solutions 3';
$invoice3->cnpj = '11.222.333/0001-44';
```

### 2. Controle Personalizado com `__clone()`
Implementação de lógica específica durante a clonagem:

```php
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

    /**
     * Executado automaticamente quando o objeto é clonado
     */
    public function __clone(): void
    {
        // Atualizar data de emissão para a data atual
        $this->issueDate = new DateTimeImmutable;
        
        // Clonar objetos complexos contidos (deep copy)
        $this->items = array_map(
            fn(Budget $budget) => clone $budget, 
            $this->items
        );
    }
}
```

### 3. Interface Prototype Personalizada
Para casos mais complexos, você pode criar uma interface específica:

```php
interface PrototypeInterface
{
    public function clone(): self;
}

class Invoice implements PrototypeInterface
{
    // ... propriedades ...

    public function clone(): self
    {
        $cloned = clone $this;
        
        // Lógica personalizada de clonagem
        $cloned->issueDate = new DateTimeImmutable;
        $cloned->items = array_map(fn($item) => clone $item, $this->items);
        
        return $cloned;
    }
}
```

## Implementação Avançada

### Registry de Protótipos
Para gerenciar múltiplos protótipos de forma organizada:

```php
class InvoicePrototypeRegistry
{
    private array $prototypes = [];

    public function registerPrototype(string $type, Invoice $prototype): void
    {
        $this->prototypes[$type] = $prototype;
    }

    public function createInvoice(string $type): Invoice
    {
        if (!isset($this->prototypes[$type])) {
            throw new InvalidArgumentException("Prototype '$type' not found");
        }

        return clone $this->prototypes[$type];
    }
}

// Uso do Registry
$registry = new InvoicePrototypeRegistry();

// Registrar protótipos
$servicePrototype = new ServiceInvoiceBuilder()
    ->withNote('Serviços de consultoria')
    ->build();

$productPrototype = new ProductInvoiceBuilder()
    ->withNote('Venda de produtos')
    ->build();

$registry->registerPrototype('service', $servicePrototype);
$registry->registerPrototype('product', $productPrototype);

// Criar instâncias rapidamente
$newServiceInvoice = $registry->createInvoice('service');
$newProductInvoice = $registry->createInvoice('product');
```

## Características do Padrão

### ✅ Vantagens
- **Performance**: Evita inicialização custosa de objetos
- **Flexibilidade**: Permite criar variações rapidamente
- **Simplicidade**: Reduz código duplicado
- **Runtime Configuration**: Protótipos podem ser configurados em tempo de execução
- **Reduz Subclasses**: Evita criar subclasses apenas para configurações diferentes

### ⚠️ Desvantagens
- **Clonagem Complexa**: Objetos com referências circulares podem ser problemáticos
- **Deep vs Shallow Copy**: Necessidade de cuidado com objetos aninhados
- **Manutenção**: Mudanças no protótipo afetam todas as cópias

## Clonagem Profunda vs Superficial

### Shallow Copy (Cópia Superficial)
```php
class Order
{
    public function __construct(
        public string $id,
        public Budget $budget  // Referência compartilhada
    ) {}
}

$original = new Order('123', $budget);
$copy = clone $original;

// ⚠️ Ambos compartilham a mesma instância de Budget
$copy->budget->setValue(500);
echo $original->budget->value(); // 500 - mudou também!
```

### Deep Copy (Cópia Profunda)
```php
class Order
{
    public function __construct(
        public string $id,
        public Budget $budget
    ) {}

    public function __clone(): void
    {
        // Clonar objetos aninhados
        $this->budget = clone $this->budget;
    }
}

$original = new Order('123', $budget);
$copy = clone $original;

// ✅ Cada um tem sua própria instância de Budget
$copy->budget->setValue(500);
echo $original->budget->value(); // Valor original mantido
```

## Exemplo Prático Completo

```php
// Cenário: Sistema de orçamentos com templates pré-configurados
class BudgetTemplate
{
    public function __construct(
        public string $type,
        public array $defaultItems,
        public float $defaultDiscount,
        public string $template
    ) {}

    public function __clone(): void
    {
        // Clonar itens para evitar compartilhamento
        $this->defaultItems = array_map(
            fn($item) => clone $item, 
            $this->defaultItems
        );
    }
}

// Criar templates base
$webTemplate = new BudgetTemplate(
    type: 'web-development',
    defaultItems: [$analysisItem, $developmentItem, $testingItem],
    defaultDiscount: 0.10,
    template: 'Desenvolvimento de sistema web completo'
);

$mobileTemplate = new BudgetTemplate(
    type: 'mobile-development', 
    defaultItems: [$designItem, $appDevelopmentItem, $publishingItem],
    defaultDiscount: 0.15,
    template: 'Desenvolvimento de aplicativo móvel'
);

// Usar protótipos para criar orçamentos rapidamente
$clientWebBudget = clone $webTemplate;
$clientWebBudget->template = 'Sistema web para e-commerce';

$clientMobileBudget = clone $mobileTemplate;
$clientMobileBudget->defaultDiscount = 0.20; // Desconto especial
```

O padrão Prototype é uma ferramenta poderosa para otimizar a criação de objetos similares, especialmente quando o custo de criação é alto ou quando você precisa de múltiplas variações de um objeto base.
