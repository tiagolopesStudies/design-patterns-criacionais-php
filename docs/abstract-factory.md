# Padrão Abstract factory

## Problemática

Em construção...

## Solução

Classe abstrata que contém os dados base de um pedido:
```php
abstract class Sale
{
    public function __construct(public readonly DateTimeImmutable $saleDate)
    {
    }
}
```

Classe que extende a classe base:
```php
class ProductSale extends Sale
{
    public function __construct(
        DateTimeImmutable $saleDate,
        public readonly float $productValue
    ) {
        parent::__construct($saleDate);
    }
}
```

Interface do fabricador de objetos das classes que herdam da classe base:
```php
interface SaleFactory
{
    public function make(): Sale;

    public function getTax(): TaxInterface;
}
```

Implementação do fabricador para um tipo de Sale:
```php
readonly class ProductSaleFactory implements SaleFactory
{
    public function __construct(
        private DateTimeImmutable $saleDate,
        private float  $productValue
    ) {
    }

    public function make(): Sale
    {
        return new ProductSale($this->saleDate, $this->productValue);
    }

    public function getTax(): TaxInterface
    {
        return new Icms();
    }
}
```
