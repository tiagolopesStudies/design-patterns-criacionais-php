<?php

declare(strict_types=1);

namespace Tiagolopes\DesignPatterns\Entity\Sale;

use DateTimeImmutable;
use Tiagolopes\DesignPatterns\Entity\Tax\Iss;
use Tiagolopes\DesignPatterns\Entity\Tax\TaxInterface;

readonly class ServiceSaleFactory implements SaleFactory
{
    public function __construct(
        private DateTimeImmutable $saleDate,
        private string $serviceName
    ) {
    }

    public function make(): Sale
    {
        return new ServiceSale($this->saleDate, $this->serviceName);
    }

    public function getTax(): TaxInterface
    {
        return new Iss();
    }
}
