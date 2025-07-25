<?php

declare(strict_types=1);

namespace Tiagolopes\DesignPatterns\Http;

interface HttpAdapter
{
    public function post(string $url, array $data): array;
}
