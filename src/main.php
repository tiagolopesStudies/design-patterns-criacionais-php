<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Tiagolopes\DesignPatterns\Entity\Budget\{Budget};
use Tiagolopes\DesignPatterns\Entity\Order\{Order};
use Tiagolopes\DesignPatterns\Database\Connection;
use Tiagolopes\DesignPatterns\Entity\Invoice\ServiceInvoiceBuilder;
use Tiagolopes\DesignPatterns\Log\FileLogManager;

$budget = new Budget(value: 1000, itemsCount: 6);

$clientName = 'Tiago Lopes';

$order = Order::create($clientName, new Budget(value: 1000, itemsCount: 6));

$filename = 'order-log.log';
$filepath = __DIR__ . '/../logs/' . $filename;
$logManager = new FileLogManager($filepath);
//$logManager->log(severity: 'info', message: 'Order created for client: ' . $clientName);

$invoice = new ServiceInvoiceBuilder()
    ->withItem($budget)
    ->withNote('test')
    ->withCompany(company: 'Test', cnpj: '12345')
    ->build();

$invoice2 = clone $invoice;
$invoice3 = clone $invoice;

echo $invoice->getTotalValue() . PHP_EOL;

$db1 = Connection::getInstance();
$db2 = Connection::getInstance();
$db3 = Connection::getInstance();

var_dump($db1, $db2, $db3);
