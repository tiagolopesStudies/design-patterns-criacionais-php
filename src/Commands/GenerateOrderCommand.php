<?php

declare(strict_types=1);

namespace Tiagolopes\DesignPatterns\Commands;

use Tiagolopes\DesignPatterns\Actions\OrderActionInterface;
use Tiagolopes\DesignPatterns\Dto\GenerateOrderDto;
use Tiagolopes\DesignPatterns\Entity\Budget\Budget;
use Tiagolopes\DesignPatterns\Entity\Order\Order;

class GenerateOrderCommand implements CommandInterface
{
    /** @var OrderActionInterface[] $actions */
    private array $actions;
    public function __construct(
        private readonly GenerateOrderDto $generateOrderDto,
        private readonly OrderServiceReceiver $orderService
    ) {
        $this->actions = [];
    }

    public function addAction(OrderActionInterface $action): void
    {
        $this->actions[] = $action;
    }

    public function execute(): void
    {
        $budget = new Budget($this->generateOrderDto->value, $this->generateOrderDto->itemsCount);
        $order  = Order::create($this->generateOrderDto->clientName, $budget);

        $this->orderService->createOrder($this->generateOrderDto->clientName, $budget);
        $this->orderService->sendEmail($this->generateOrderDto->clientName);

        foreach ($this->actions as $action) {
            $action->execute($order);
        }
    }
}
