<?php

declare(strict_types=1);

namespace Tiagolopes\DesignPatterns\Services;

use Tiagolopes\DesignPatterns\Entity\Budget\Budget;
use Tiagolopes\DesignPatterns\Entity\Tax\TaxInterface;

class TaxCalculator
{
    public function calculate(Budget $budget, TaxInterface $tax): float
    {
        return $tax->calculate($budget);
    }
}
