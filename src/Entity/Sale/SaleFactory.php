<?php

declare(strict_types=1);

namespace Tiagolopes\DesignPatterns\Entity\Sale;

use Tiagolopes\DesignPatterns\Entity\Tax\TaxInterface;

interface SaleFactory
{
    public function make(): Sale;

    public function getTax(): TaxInterface;
}
