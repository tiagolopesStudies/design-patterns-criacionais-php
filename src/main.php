<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Tiagolopes\DesignPatterns\Entity\Tax\{Icms, Iptu, Iss};
use Tiagolopes\DesignPatterns\Services\{DiscountCalculator, TaxCalculator};
use Tiagolopes\DesignPatterns\Entity\Budget\{Budget, BudgetList, BudgetListCacheProxy};
use Tiagolopes\DesignPatterns\Entity\Order\{Order, OrderList};

$budget             = new Budget(value: 1000, itemsCount: 6);
$taxCalculator      = new TaxCalculator();
$discountCalculator = new DiscountCalculator();

$icms     = new Icms;
$withIptu = new Iptu($icms);
$withIss  = new Iss($withIptu);

echo $taxCalculator->calculate(budget: $budget, tax: $withIss) . PHP_EOL;
echo $discountCalculator->calculate(budget: $budget) . PHP_EOL;

$budget->approve();
echo $budget->calculateExtraDiscount() . PHP_EOL;

$clientName = 'Tiago Lopes';

$order1 = Order::create($clientName, new Budget(value: 1000, itemsCount: 6));
$order2 = Order::create($clientName, new Budget(value: 5000, itemsCount: 1));
$order3 = Order::create($clientName, new Budget(value: 10000, itemsCount: 10));

$orderList = new OrderList();
$orderList->add($order1, $order2, $order3);

foreach ($orderList as $order) {
    echo "Client: " . $order->clientName() . PHP_EOL;
    echo "Value: {$order->budget->value}" . PHP_EOL;
    echo "Items: {$order->budget->itemsCount}" . PHP_EOL;
    echo PHP_EOL;
}

$oldBudgetList = new BudgetList;
$oldBudgetList->addItem($budget);

$budgetList = new BudgetList;
$budgetList->addItem($oldBudgetList);
$budgetList->addItem($budget);

$cacheProxy = new BudgetListCacheProxy($budgetList);

echo $cacheProxy->value() . PHP_EOL;
echo $cacheProxy->value() . PHP_EOL;
echo $cacheProxy->value() . PHP_EOL;
