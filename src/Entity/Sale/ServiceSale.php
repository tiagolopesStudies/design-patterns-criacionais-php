<?php

declare(strict_types=1);

namespace Tiagolopes\DesignPatterns\Entity\Sale;

use DateTimeImmutable;

class ServiceSale extends Sale
{
    public function __construct(
        DateTimeImmutable $saleDate,
        public readonly string $serviceName
    ) {
        parent::__construct($saleDate);
    }
}
