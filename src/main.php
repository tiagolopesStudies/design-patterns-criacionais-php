<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Tiagolopes\DesignPatterns\Entity\Tax\{Icms, Iptu, Iss};
use Tiagolopes\DesignPatterns\Services\{DiscountCalculator, TaxCalculator};
use Tiagolopes\DesignPatterns\Entity\Budget\{Budget, BudgetList, BudgetListCacheProxy};
use Tiagolopes\DesignPatterns\Entity\Order\{Order, OrderList};
use Tiagolopes\DesignPatterns\Log\FileLogManager;

$budget = new Budget(value: 1000, itemsCount: 6);

$clientName = 'Tiago Lopes';

$order = Order::create($clientName, new Budget(value: 1000, itemsCount: 6));

$filename = 'order-log.log';
$filepath = __DIR__ . '/../logs/' . $filename;
$logManager = new FileLogManager($filepath);
$logManager->log(severity: 'info', message: 'Order created for client: ' . $clientName);
