<?php

declare(strict_types=1);

namespace Tiagolopes\DesignPatterns\Log;

class FileLogManager extends LogManager
{
    public function __construct(public string $filepath)
    {
    }

    public function makeLogWriter(): LogWriter
    {
        return new FileLogWriter($this->filepath);
    }
}
