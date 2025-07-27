# Padrão Prototype

## Problemática

Objetos com muitos dados iguais, mudando apenas algumas informações:
```php
$invoice = new ServiceInvoiceBuilder()
    ->withItem($budget)
    ->withNote('test')
    ->withCompany(company: 'Test', cnpj: '12345')
    ->build();

$invoice2 = new ServiceInvoiceBuilder()
    ->withItem($budget)
    ->withNote('test')
    ->withCompany(company: 'Test2', cnpj: '54321')
    ->build();

$invoice3 = new ServiceInvoiceBuilder()
    ->withItem($budget)
    ->withNote('test')
    ->withCompany(company: 'Test3', cnpj: '44325')
    ->build();
```

## Solução

Utilização da palavra reservada `clone` do PHP ou criação de método para clonagem de objeto:
```php
$invoice = new ServiceInvoiceBuilder()
    ->withItem($budget)
    ->withNote('test')
    ->withCompany(company: 'Test', cnpj: '12345')
    ->build();

$invoice2 = clone $invoice;
$invoice3 = clone $invoice;
```

No PHP também é possível realizar ações após a clonagem através do método `__clone`:
```php
public function __clone(): void
{
    $this->issueDate = new DateTimeImmutable;
}
```
