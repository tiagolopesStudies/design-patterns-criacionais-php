<?php

declare(strict_types=1);

namespace Tiagolopes\DesignPatterns\Entity\Sale;

use DateTimeImmutable;

abstract class Sale
{
    public function __construct(public readonly DateTimeImmutable $saleDate)
    {
    }
}
