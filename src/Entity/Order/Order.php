<?php

declare(strict_types=1);

namespace Tiagolopes\DesignPatterns\Entity\Order;

use DateTimeImmutable;
use Tiagolopes\DesignPatterns\Entity\Budget\Budget;

class Order
{
    private(set) OrderTemplate $template;
    private(set) Budget $budget;

    private function __construct()
    {
    }

    public static function create(
        string $clientName,
        Budget $budget,
        ?DateTimeImmutable $finalizedAt = null
    ): self {
        $instance = new self();

        $instance->template = new OrderTemplate($clientName, $finalizedAt);
        $instance->budget   = $budget;

        return $instance;
    }

    public static function createFromTemplate(OrderTemplate $template, Budget $budget): self
    {
        $instance = new self();

        $instance->template = $template;
        $instance->budget   = $budget;

        return $instance;
    }

    public function clientName(): string
    {
        return $this->template->clientName;
    }
}
