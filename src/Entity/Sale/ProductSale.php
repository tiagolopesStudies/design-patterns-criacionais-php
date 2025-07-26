<?php

declare(strict_types=1);

namespace Tiagolopes\DesignPatterns\Entity\Sale;

use DateTimeImmutable;

class ProductSale extends Sale
{
    public function __construct(
        DateTimeImmutable $saleDate,
        public readonly float $productValue
    ) {
        parent::__construct($saleDate);
    }
}
