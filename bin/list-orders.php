<?php

declare(strict_types=1);

use Tiagolopes\DesignPatterns\Entity\Budget\Budget;
use Tiagolopes\DesignPatterns\Entity\Order\{Order, OrderTemplate};
use Tiagolopes\DesignPatterns\Factory\OrderFactory;

require __DIR__ . '/../vendor/autoload.php';

if (count($argv) < 2 || count($argv) > 3) {
    echo 'Usage: php create-order.php <budget-value> [items-count]' . PHP_EOL;
    exit(1);
}

$budgetValue  = (float) $argv[1];
$itemsCount   = isset($argv[2]) ? (int) $argv[2] : 1;
$budget       = new Budget(value: $budgetValue, itemsCount: $itemsCount);
$orders       = [];
$orderFactory = new OrderFactory();
$clientName   = 'Tiago Lopes';
$finalizedAt  = new DateTimeImmutable();

for ($i = 0; $i < 10000; $i++) {
    $orders[] = $orderFactory->make(
        clientName: $clientName,
        budget: $budget,
        finalizedAt: $finalizedAt
    );
}

$memoryInMb = round(num: (memory_get_peak_usage() / 1024.0 / 1024.0), precision: 2);
echo "Memory usage: $memoryInMb MB" . PHP_EOL;
