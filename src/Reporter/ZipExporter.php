<?php

declare(strict_types=1);

namespace Tiagolopes\DesignPatterns\Reporter;

use ZipArchive;

class ZipExporter implements FileExporter
{
    public function __construct(public string $filename)
    {
    }

    public function export(ExportedContent $content): string
    {
        $filepath = __DIR__ . "/../../temp/$this->filename";

        $zip = new ZipArchive();
        $zip->open($filepath, ZipArchive::CREATE);
        $zip->addFromString('content.serialize', serialize($content));
        $zip->close();

        return realpath($filepath);
    }
}
