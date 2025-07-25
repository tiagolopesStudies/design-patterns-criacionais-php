<?php

declare(strict_types=1);

namespace Tiagolopes\DesignPatterns\Entity\Status;

use Tiagolopes\DesignPatterns\Entity\Budget\Budget;

class Pending extends BudgetState
{
    public function calculateExtraDiscount(Budget $budget): float
    {
        return 0;
    }

    public function approve(Budget $budget): void
    {
        $budget->status = new Approved;
    }

    public function reprove(Budget $budget): void
    {
        $budget->status = new Reproved;
    }
}
