<?php

declare(strict_types=1);

namespace Tiagolopes\DesignPatterns\Reporter;

interface FileExporter
{
    public function export(ExportedContent $content): string;
}
