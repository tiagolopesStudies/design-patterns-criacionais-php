<?php

declare(strict_types=1);

namespace Tiagolopes\DesignPatterns\Reporter;

use Tiagolopes\DesignPatterns\Entity\Budget\Budget;

readonly class ExportedBudget implements ExportedContent
{
    public function __construct(private Budget $budget)
    {
    }

    public function getContent(): array
    {
        return [
            'value' => $this->budget->value,
            'items_count' => $this->budget->itemsCount,
            'status' => get_class($this->budget->status),
        ];
    }
}
