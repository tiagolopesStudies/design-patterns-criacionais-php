<?php

declare(strict_types=1);

namespace Tiagolopes\DesignPatterns\Entity\Status;

use DomainException;
use Tiagolopes\DesignPatterns\Entity\Budget\Budget;

class Finalized extends BudgetState
{
    public function calculateExtraDiscount(Budget $budget): float
    {
        throw new DomainException('Budget already finalized.');
    }
}
