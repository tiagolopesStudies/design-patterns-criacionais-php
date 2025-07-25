<?php

declare(strict_types=1);

namespace Tiagolopes\DesignPatterns\Entity\Tax;

use Tiagolopes\DesignPatterns\Entity\Budget\Budget;

abstract class TaxWith2Aliquots extends Tax
{
    public function calculateSpecificTax(Budget $budget): float
    {
        if ($this->shouldApplyMaxTax($budget)) {
            return $this->calculateMaxTax($budget);
        }

        return $this->calculateMinTax($budget);
    }

    abstract protected function shouldApplyMaxTax(Budget $budget): bool;
    abstract protected function calculateMaxTax(Budget $budget): float;
    abstract protected function calculateMinTax(Budget $budget): float;
}
