<?php

declare(strict_types=1);

namespace Tiagolopes\DesignPatterns\Logger;

use Tiagolopes\DesignPatterns\Entity\Budget\Budget;

class DiscountLogger
{
    public static function saveLog(Budget $budget, float $discount): void
    {
        $filepath = __DIR__ . '/../../logs/discounts.log';
        if (! file_exists($filepath)) {
            touch($filepath);
        }

        $status   = get_class($budget->status);
        $datetime = date('Y-m-d H:i:s');

        $content = <<<TEXT
            =========================
            {$datetime}
            value: {$budget->value}
            discount: {$discount}
            items_count: {$budget->itemsCount}
            status: {$status}
        TEXT;

        file_put_contents(filename: $filepath, data: $content, flags: FILE_APPEND);

        // More complex code
    }
}
