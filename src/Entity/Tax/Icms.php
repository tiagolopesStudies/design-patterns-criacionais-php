<?php

declare(strict_types=1);

namespace Tiagolopes\DesignPatterns\Entity\Tax;

use Tiagolopes\DesignPatterns\Entity\Budget\Budget;

class Icms extends Tax
{
    public function calculateSpecificTax(Budget $budget): float
    {
        return $budget->value * 0.1;
    }
}
