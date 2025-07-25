<?php

declare(strict_types=1);

namespace Tiagolopes\DesignPatterns\Reporter;

use Tiagolopes\DesignPatterns\Entity\Order\Order;

readonly class ExportedOrder implements ExportedContent
{
    public function __construct(private Order $order)
    {
    }

    public function getContent(): array
    {
        return [
            'client' => $this->order->clientName,
            'budget_value' => $this->order->budget->value,
            'finalized_at' => $this->order->finalizedAt?->format('Y-m-d H:i:s') ?? 'Not finalized yet.',
        ];
    }
}
