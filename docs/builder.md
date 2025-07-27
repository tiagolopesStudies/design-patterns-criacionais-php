# Padrão Builder

## Problemática

Construtor com muitos parâmetros:
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

Utilização:
```php
$invoice = new Invoice(
    cnpj: 'Cnpj',
    companyName: 'Company Name',
    items: [],
    observacoes: null,
    issueDate: new DateTimeImmutable(),
    taxValue: 0.0
);
```

## Solução

Criação de builder abstrato com métodos para preenchimento das informações em etapas:
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

    abstract public function build(): Invoice;
}
```

Criando builder específico que extende a classe abstrata:
```php
class ServiceInvoiceBuilder extends InvoiceBuilder
{
    public function build(): Invoice
    {
        $total = $this->invoice->getTotalValue();
        $this->invoice->taxValue = $total * 0.3;

        return $this->invoice;
    }
}
```

Utilização:
```php
$budget  = new Budget(value: 1000, itemsCount: 6);
$invoice = new ServiceInvoiceBuilder()
    ->withItem($budget)
    ->withNote('This is a noite')
    ->withCompany(company: 'Company name', cnpj: '12345')
    ->build();
```
