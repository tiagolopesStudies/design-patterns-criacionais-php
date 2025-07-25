<?php

declare(strict_types=1);

namespace Tiagolopes\DesignPatterns\Log;

class StdoutLogManager extends LogManager
{
    public function makeLogWriter(): LogWriter
    {
        return new StdoutLogWriter();
    }
}
