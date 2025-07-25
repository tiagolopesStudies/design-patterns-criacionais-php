<?php

declare(strict_types=1);

namespace Tiagolopes\DesignPatterns\Entity\Tax;

use Tiagolopes\DesignPatterns\Entity\Budget\Budget;

interface TaxInterface
{
    public function calculate(Budget $budget): float;
}
