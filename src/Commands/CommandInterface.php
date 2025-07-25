<?php

declare(strict_types=1);

namespace Tiagolopes\DesignPatterns\Commands;

interface CommandInterface
{
    public function execute(): void;
}
