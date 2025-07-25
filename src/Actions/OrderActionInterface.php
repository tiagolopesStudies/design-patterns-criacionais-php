<?php

declare(strict_types=1);

namespace Tiagolopes\DesignPatterns\Actions;

use Tiagolopes\DesignPatterns\Entity\Order\Order;

interface OrderActionInterface
{
    public function execute(Order $order): void;
}
