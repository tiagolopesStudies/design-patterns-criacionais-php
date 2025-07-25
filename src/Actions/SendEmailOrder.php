<?php

declare(strict_types=1);

namespace Tiagolopes\DesignPatterns\Actions;

use Tiagolopes\DesignPatterns\Entity\Order\Order;

class SendEmailOrder implements OrderActionInterface
{
    public function execute(Order $order): void
    {
        echo "Sending email to '$order->clientName'..." . PHP_EOL;
    }
}
