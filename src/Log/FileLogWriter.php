<?php

declare(strict_types=1);

namespace Tiagolopes\DesignPatterns\Log;

use RuntimeException;

class FileLogWriter implements LogWriter
{
    /** @var resource $file */
    private $file;
    public function __construct(string $filepath)
    {
        if (! file_exists($filepath)) {
            touch($filepath);
        }

        $file = fopen(filename: $filepath, mode: 'a+');
        if (! $file) {
            throw new RuntimeException('Could not open file');
        }

        $this->file = $file;
    }

    public function write(string $message): void
    {
        fwrite($this->file, $message);
    }

    public function __destruct()
    {
        fclose($this->file);
    }
}
