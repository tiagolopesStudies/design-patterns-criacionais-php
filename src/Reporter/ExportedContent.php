<?php

declare(strict_types=1);

namespace Tiagolopes\DesignPatterns\Reporter;

interface ExportedContent
{
    public function getContent(): array;
}
