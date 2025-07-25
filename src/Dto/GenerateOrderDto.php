<?php

declare(strict_types=1);

namespace Tiagolopes\DesignPatterns\Dto;

readonly class GenerateOrderDto
{
    public function __construct(
        public string $clientName,
        public float $value,
        public int $itemsCount = 1
    ) {
    }
}
