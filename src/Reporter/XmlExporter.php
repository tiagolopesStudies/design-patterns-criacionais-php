<?php

declare(strict_types=1);

namespace Tiagolopes\DesignPatterns\Reporter;

use RuntimeException;
use SimpleXMLElement;

class XmlExporter implements FileExporter
{
    public function __construct(public string $filename)
    {
    }

    public function export(ExportedContent $content): string
    {
        $xml = new SimpleXMLElement('<content />');

        foreach ($content->getContent() as $key => $value) {
            $xml->addChild(qualifiedName: $key, value: (string) $value);
        }

        $filepath = __DIR__ . "/../../temp/$this->filename";
        $result = $xml->asXML(filename: $filepath);
        if (! $result) {
            throw new RuntimeException('Error exporting content to XML.');
        }

        return realpath($filepath);
    }
}
