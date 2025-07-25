<?php

declare(strict_types=1);

namespace Tiagolopes\DesignPatterns\Http;

use Exception;
use GuzzleHttp\Client as GuzzleClient;
use Throwable;

readonly class GuzzleHttpAdapter implements HttpAdapter
{
    public function __construct(private GuzzleClient $client)
    {
    }

    public function post(string $url, array $data): array
    {
        try {
            $response = $this->client->post(
                uri: $url,
                options: [
                    'json' => $data,
                ]
            );

            if ($response->getStatusCode() !== 201) {
                throw new Exception();
            }

            return json_decode(json: $response->getBody()->getContents(), associative: true);
        } catch (Throwable) {
            return [
                'error' => 'API not available.'
            ];
        }
    }
}
