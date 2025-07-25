<?php

declare(strict_types=1);

namespace Tiagolopes\DesignPatterns\Actions;

use Tiagolopes\DesignPatterns\Entity\Order\Order;

class OrderLogger implements OrderActionInterface
{
    public function execute(Order $order): void
    {
        echo "Logging order..." . PHP_EOL;
    }
}
