<?php

declare(strict_types=1);

namespace Tiagolopes\DesignPatterns\Log;

interface LogWriter
{
    public function write(string $message): void;
}
