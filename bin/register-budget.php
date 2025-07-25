<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Tiagolopes\DesignPatterns\Entity\Budget\Budget;
use Tiagolopes\DesignPatterns\Http\{GuzzleHttpAdapter};
use Tiagolopes\DesignPatterns\Services\BudgetRegister;

if (count($argv) < 2 || count($argv) > 3) {
    echo 'Usage: php create-order.php <budget-value> [items-count]' . PHP_EOL;
    exit(1);
}

$budgetValue = (float) $argv[1];
$itemsCount  = isset($argv[2]) ? (int) $argv[2] : 1;
$budget      = new Budget($budgetValue, $itemsCount);

$budget->approve();
$budget->finalize();

$budgetRegister = new BudgetRegister(new GuzzleHttpAdapter(new \GuzzleHttp\Client()));
$result = $budgetRegister->register($budget);
print_r($result);
