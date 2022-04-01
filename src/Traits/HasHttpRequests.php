<?php

namespace Overtrue\Http\Traits;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;

trait HasHttpRequests
{
    protected ?ClientInterface $httpClient = null;

    protected static array $defaults = [
        'curl' => [
            CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
        ],
    ];

    public static function setDefaultOptions(array $defaults = [])
    {
        self::$defaults = $defaults;
    }

    public static function getDefaultOptions(): array
    {
        return self::$defaults;
    }

    public function setHttpClient(ClientInterface $httpClient): static
    {
        $this->httpClient = $httpClient;

        return $this;
    }

    public function getHttpClient(): ClientInterface
    {
        if (!$this->httpClient) {
            $this->httpClient = new Client(['handler' => $this->getHandlerStack()]);
        }

        return $this->httpClient;
    }

    public function request(string $uri, string $method = 'GET', array $options = [], bool $async = false)
    {
        return $this->getHttpClient()->{ $async ? 'requestAsync' : 'request' }(strtoupper($method), $uri, array_merge(self::$defaults, $options));
    }
}
