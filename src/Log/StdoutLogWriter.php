<?php

declare(strict_types=1);

namespace Tiagolopes\DesignPatterns\Log;

class StdoutLogWriter implements LogWriter
{
    public function write(string $message): void
    {
        var_dump($message);
    }
}
