<?php

declare(strict_types=1);

namespace Tiagolopes\DesignPatterns\Entity\Status;

use Tiagolopes\DesignPatterns\Entity\Budget\Budget;

class Reproved extends BudgetState
{
    public function calculateExtraDiscount(Budget $budget): float
    {
        return 0;
    }

    public function finalize(Budget $budget): void
    {
        $budget->status = new Finalized;
    }
}
