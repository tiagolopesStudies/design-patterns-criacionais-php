<?php

declare(strict_types=1);

namespace Tiagolopes\DesignPatterns\Factory;

use DateTimeImmutable;
use Tiagolopes\DesignPatterns\Entity\Budget\Budget;
use Tiagolopes\DesignPatterns\Entity\Order\{Order, OrderTemplate};

class OrderFactory
{
    private array $templatesCache;
    public function __construct()
    {
        $this->templatesCache = [];
    }

    public function make(
        string $clientName,
        Budget $budget,
        ?DateTimeImmutable $finalizedAt = null
    ): Order {
        $template = $this->generateTemplate($clientName, $finalizedAt);

        return Order::createFromTemplate($template, $budget);
    }

    private function generateTemplate(string $clientName, ?DateTimeImmutable $finalizedAt = null): OrderTemplate
    {
        $hash = md5($clientName . ($finalizedAt?->format('Y-m-d') ?? 'not-finalized'));

        if (!isset($this->templatesCache[$hash])) {
            $this->templatesCache[$hash] = new OrderTemplate($clientName, $finalizedAt);
        }

        return $this->templatesCache[$hash];
    }
}
