<?php

declare(strict_types=1);

namespace Tiagolopes\DesignPatterns\Entity\Sale;

use DateTimeImmutable;
use Tiagolopes\DesignPatterns\Entity\Tax\Icms;
use Tiagolopes\DesignPatterns\Entity\Tax\TaxInterface;

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
