<?php

declare(strict_types=1);

namespace Tiagolopes\DesignPatterns\Entity\Order;

use ArrayIterator;
use IteratorAggregate;
use Traversable;

class OrderList implements IteratorAggregate
{
    /** @var array<Order> $orders */
    private array $orders;

    public function __construct()
    {
        $this->orders = [];
    }

    public function add(Order ...$orders): void
    {
        array_push($this->orders, ...$orders);
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->orders);
    }
}
