<?php

declare(strict_types=1);

namespace Tiagolopes\DesignPatterns\Entity\Discount;

use Tiagolopes\DesignPatterns\Entity\Budget\Budget;

class DiscountMoreThan5Items extends Discount
{
    protected function shouldApply(Budget $budget): bool
    {
        return $budget->itemsCount > 5;
    }

    protected function calculateDiscount(Budget $budget): float
    {
        return $budget->value * 0.1;
    }
}
