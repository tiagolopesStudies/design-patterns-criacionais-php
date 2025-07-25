<?php

declare(strict_types=1);

namespace Tiagolopes\DesignPatterns\Entity\Tax;

use Tiagolopes\DesignPatterns\Entity\Budget\Budget;

abstract class Tax implements TaxInterface
{
    public function __construct(private readonly ?Tax $anotherTask = null)
    {
    }

    abstract protected function calculateSpecificTax(Budget $budget): float;

    public function calculate(Budget $budget): float
    {
        return $this->calculateSpecificTax($budget) + $this->anotherTask?->calculate($budget) ?? 0;
    }
}
