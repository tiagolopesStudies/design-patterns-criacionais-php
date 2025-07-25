<?php

declare(strict_types=1);

namespace Tiagolopes\DesignPatterns\Http;

use Exception;
use Throwable;

class CurlHttpAdapter implements HttpAdapter
{
    public function post(string $url, array $data): array
    {
        try {
            $curl = curl_init($url);
            curl_setopt(handle: $curl, option: CURLOPT_POST, value: true);
            curl_setopt(handle: $curl, option: CURLOPT_POSTFIELDS, value: $data);
            curl_setopt(handle: $curl, option: CURLOPT_RETURNTRANSFER, value: true);
            $response = curl_exec($curl);
            curl_close($curl);

            if ($response === false) {
                throw new Exception();
            }

            return json_decode(json: $response, associative: true);
        } catch (Throwable) {
            return [
                'error' => 'API not available.'
            ];
        }
    }
}
