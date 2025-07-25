<?php

declare(strict_types=1);

namespace Tiagolopes\DesignPatterns\Log;

abstract class LogManager
{
    public function log(string $severity, string $message): void
    {
        $logWriter = $this->makeLogWriter();

        $today = date('Y-m-d H:i:s');
        $logWriter->write("[$today]-[$severity]: $message" . PHP_EOL);
    }

    abstract public function makeLogWriter(): LogWriter;
}
