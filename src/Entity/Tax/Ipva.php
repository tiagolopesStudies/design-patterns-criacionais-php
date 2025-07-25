<?php

declare(strict_types=1);

namespace Tiagolopes\DesignPatterns\Entity\Tax;

use Tiagolopes\DesignPatterns\Entity\Budget\Budget;

class Ipva extends TaxWith2Aliquots
{
    protected function shouldApplyMaxTax(Budget $budget): bool
    {
        return $budget->itemsCount < 3;
    }

    protected function calculateMaxTax(Budget $budget): float
    {
        return $budget->value * 0.05;
    }

    protected function calculateMinTax(Budget $budget): float
    {
        return $budget->value * 0.025;
    }
}
