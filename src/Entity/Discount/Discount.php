<?php

declare(strict_types=1);

namespace Tiagolopes\DesignPatterns\Entity\Discount;

use Tiagolopes\DesignPatterns\Entity\Budget\Budget;

abstract class Discount
{
    public function __construct(protected ?Discount $nextDiscount = null)
    {
    }

    public function calculate(Budget $budget): float
    {
        if ($this->shouldApply($budget)) {
            return $this->calculateDiscount($budget);
        }

        return $this->nextDiscount?->calculate($budget) ?? 0;
    }

    abstract protected function shouldApply(Budget $budget): bool;

    abstract protected function calculateDiscount(Budget $budget): float;
}
