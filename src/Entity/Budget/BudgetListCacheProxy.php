<?php

declare(strict_types=1);

namespace Tiagolopes\DesignPatterns\Entity\Budget;

use Exception;

class BudgetListCacheProxy extends BudgetList
{
    private ?float $cachedValue = null;

    public function __construct(private readonly BudgetList $budgetList)
    {
        parent::__construct();
    }

    public function addItem(Budgetable $budget): void
    {
        throw new Exception('Could not add item to a cached proxy budget list.');
    }

    public function value(): float
    {
        if ($this->cachedValue === null) {
            $this->cachedValue = $this->budgetList->value();
        }

        return $this->cachedValue;
    }
}
