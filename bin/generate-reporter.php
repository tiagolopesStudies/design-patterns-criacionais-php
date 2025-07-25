<?php

declare(strict_types=1);

use Tiagolopes\DesignPatterns\Reporter\{ExportedOrder, XmlExporter, ZipExporter};
use Tiagolopes\DesignPatterns\Entity\Budget\Budget;
use Tiagolopes\DesignPatterns\Entity\Order\Order;

require __DIR__ . '/../vendor/autoload.php';

if (count($argv) < 3 || count($argv) > 5) {
    echo 'Usage: php create-order.php "<client-name>" <budget-value> [items-count] [format]' . PHP_EOL;
    exit(1);
}

$clientName  = $argv[1];
$budgetValue = (float) $argv[2];
$itemsCount  = isset($argv[3]) ? (int) $argv[3] : 1;
$format      = isset($argv[4]) ? strtolower($argv[4]) : 'zip';

if (! in_array($format, ['zip', 'xml'])) {
    echo 'The extension should be "xml" or "zip"' . PHP_EOL;
    exit(1);
}

$budget = new Budget(value: $budgetValue, itemsCount: $itemsCount);
$budget->approve();
$budget->finalize();

$order = Order::create(
    clientName: $clientName,
    budget: $budget,
    finalizedAt: new DateTimeImmutable()
);

$filename          = 'budget-' . date('Y-m-d h:i:s') . ".$format";
$xmlBudgetExporter = $format === 'zip'
    ? new ZipExporter($filename)
    : new XmlExporter($filename);

$xmlBudgetExporter->export(new ExportedOrder($order));
