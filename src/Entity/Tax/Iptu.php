<?php

declare(strict_types=1);

namespace Tiagolopes\DesignPatterns\Entity\Tax;

use Tiagolopes\DesignPatterns\Entity\Budget\Budget;

class Iptu extends TaxWith2Aliquots
{
    protected function shouldApplyMaxTax(Budget $budget): bool
    {
        return $budget->value < 1000;
    }

    protected function calculateMaxTax(Budget $budget): float
    {
        return $budget->value * 0.05;
    }

    protected function calculateMinTax(Budget $budget): float
    {
        return $budget->value * 0.01;
    }
}
