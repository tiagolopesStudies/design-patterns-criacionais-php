<?php

declare(strict_types=1);

namespace Tiagolopes\DesignPatterns\Entity\Status;

use DomainException;
use Tiagolopes\DesignPatterns\Entity\Budget\Budget;

abstract class BudgetState
{
    abstract public function calculateExtraDiscount(Budget $budget): float;

    public function approve(Budget $budget): void
    {
        throw new DomainException('This budget could not be approved.');
    }

    public function reprove(Budget $budget): void
    {
        throw new DomainException('This budget could not be reproved.');
    }

    public function finalize(Budget $budget): void
    {
        throw new DomainException('This budget could not be finalized.');
    }
}
