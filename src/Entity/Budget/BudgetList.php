<?php

declare(strict_types=1);

namespace Tiagolopes\DesignPatterns\Entity\Budget;

class BudgetList implements Budgetable
{
    /** @var array<Budgetable> $items */
    private array $items;

    public function __construct()
    {
        $this->items = [];
    }

    public function addItem(Budgetable $budget): void
    {
        $this->items[] = $budget;
    }

    public function value(): float
    {
        sleep(2); // simulates the response time of an API

        return array_reduce(
            array: $this->items,
            callback: fn (float $total, Budgetable $budget) => $total + $budget->value(),
            initial: 0.0
        );
    }
}
