<?php

declare(strict_types=1);

namespace Tiagolopes\DesignPatterns\Entity\Status;

use Tiagolopes\DesignPatterns\Entity\Budget\Budget;

class Approved extends BudgetState
{
    public function calculateExtraDiscount(Budget $budget): float
    {
        return $budget->value * 0.05;
    }

    public function finalize(Budget $budget): void
    {
        $budget->status = new Finalized;
    }
}
