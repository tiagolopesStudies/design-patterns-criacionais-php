<?php

declare(strict_types=1);

namespace Tiagolopes\DesignPatterns\Entity\Budget;

use Tiagolopes\DesignPatterns\Entity\Status\BudgetState;
use Tiagolopes\DesignPatterns\Entity\Status\Pending;

class Budget implements Budgetable
{
    public BudgetState $status;
    public function __construct(
        public float $value,
        public int $itemsCount = 1
    ) {
        $this->status = new Pending;
    }

    public function calculateExtraDiscount(): float
    {
        return $this->status->calculateExtraDiscount($this);
    }

    public function approve(): void
    {
        $this->status->approve($this);
    }

    public function reprove(): void
    {
        $this->status->reprove($this);
    }

    public function finalize(): void
    {
        $this->status->finalize($this);
    }

    public function value(): float
    {
        return $this->value;
    }
}
