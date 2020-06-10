<?php

/*
 * This file is part of the overtrue/http.
 *
 * (c) overtrue <i@overtrue.me>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Overtrue\Http\Traits;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;

/**
 * Trait HasHttpRequests.
 */
trait HasHttpRequests
{
    /**
     * @var \GuzzleHttp\ClientInterface
     */
    protected $httpClient;

    /**
     * @var array
     */
    protected static $defaults = [
        'curl' => [
            CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
        ],
    ];

    /**
     * Set guzzle default settings.
     *
     * @param array $defaults
     */
    public static function setDefaultOptions($defaults = [])
    {
        self::$defaults = $defaults;
    }

    /**
     * Return current guzzle default settings.
     *
     * @return array
     */
    public static function getDefaultOptions(): array
    {
        return self::$defaults;
    }

    /**
     * Set GuzzleHttp\Client.
     *
     * @param \GuzzleHttp\ClientInterface $httpClient
     *
     * @return $this
     */
    public function setHttpClient(ClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;

        return $this;
    }

    /**
     * Return GuzzleHttp\Client instance.
     *
     * @return \GuzzleHttp\ClientInterface
     */
    public function getHttpClient(): ClientInterface
    {
        if (!$this->httpClient) {
            $this->httpClient = new Client(['handler' => $this->getHandlerStack()]);
        }

        return $this->httpClient;
    }

    /**
     * Make a request.
     *
     * @param string $uri
     * @param string $method
     * @param array  $options
     * @param bool   $async
     *
     * @return \Psr\Http\Message\ResponseInterface|\GuzzleHttp\Promise\Promise
     */
    public function request($uri, $method = 'GET', $options = [], bool $async = false)
    {
        return $this->getHttpClient()->{ $async ? 'requestAsync' : 'request' }(strtoupper($method), $uri, array_merge(self::$defaults, $options));
    }
}
