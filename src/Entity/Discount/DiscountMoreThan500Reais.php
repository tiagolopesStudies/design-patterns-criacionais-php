<?php

declare(strict_types=1);

namespace Tiagolopes\DesignPatterns\Entity\Discount;

use Tiagolopes\DesignPatterns\Entity\Budget\Budget;

class DiscountMoreThan500Reais extends Discount
{
    protected function shouldApply(Budget $budget): bool
    {
        return $budget->value > 500;
    }

    protected function calculateDiscount(Budget $budget): float
    {
        return $budget->value * 0.05;
    }
}
