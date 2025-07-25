<?php

declare(strict_types=1);

namespace Tiagolopes\DesignPatterns\Commands;

use Tiagolopes\DesignPatterns\Entity\Budget\Budget;

class OrderServiceReceiver
{
    public function createOrder(string $clientName, Budget $budget): void
    {
        echo "Creating order in database..." . PHP_EOL;
    }

    public function sendEmail(string $clientName): void
    {
        echo "Sending email to {$clientName}..." . PHP_EOL;
    }
}
