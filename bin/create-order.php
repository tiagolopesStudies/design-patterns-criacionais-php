<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Tiagolopes\DesignPatterns\Actions\OrderLogger;
use Tiagolopes\DesignPatterns\Commands\CommandInvoker;
use Tiagolopes\DesignPatterns\Commands\GenerateOrderCommand;
use Tiagolopes\DesignPatterns\Commands\OrderServiceReceiver;
use Tiagolopes\DesignPatterns\Dto\GenerateOrderDto;

if (count($argv) < 3 || count($argv) > 4) {
    echo 'Usage: php create-order.php "<client-name>" <budget-value> [items-count]' . PHP_EOL;
    exit(1);
}

$clientName  = $argv[1];
$budgetValue = (float) $argv[2];
$itemsCount  = isset($argv[3]) ? (int) $argv[3] : 1;

$generateOrder = new GenerateOrderDto($clientName, $budgetValue, $itemsCount);
$command       = new GenerateOrderCommand($generateOrder, new OrderServiceReceiver);
$invoker       = new CommandInvoker;

$command->addAction(new OrderLogger);

$invoker->addCommand($command);
$invoker->executeCommands();
