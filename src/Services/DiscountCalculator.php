<?php

declare(strict_types=1);

namespace Tiagolopes\DesignPatterns\Services;

use Tiagolopes\DesignPatterns\Entity\Budget\Budget;
use Tiagolopes\DesignPatterns\Entity\Discount\DiscountMoreThan500Reais;
use Tiagolopes\DesignPatterns\Entity\Discount\DiscountMoreThan5Items;
use Tiagolopes\DesignPatterns\Logger\DiscountLogger;

class DiscountCalculator
{
    public function calculate(Budget $budget): float
    {
        $discount = (new DiscountMoreThan5Items(
            new DiscountMoreThan500Reais
        ))->calculate($budget);

        DiscountLogger::saveLog($budget, $discount);

        return $discount;
    }
}
