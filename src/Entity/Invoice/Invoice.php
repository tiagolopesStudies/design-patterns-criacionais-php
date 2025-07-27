<?php

declare(strict_types=1);

namespace Tiagolopes\DesignPatterns\Entity\Invoice;

use DateTimeImmutable;
use Tiagolopes\DesignPatterns\Entity\Budget\Budget;

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
        $this->items     = [];
        $this->issueDate = new DateTimeImmutable;
    }

    public function getTotalValue(): float
    {
        if (empty($this->items)) {
            return 0;
        }

        return array_reduce(
            array: $this->items,
            callback: fn ($total, Budget $item) => $total + $item->value()
        );
    }
}
