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
use GuzzleHttp\HandlerStack;
use Psr\Http\Message\ResponseInterface;

/**
 * Trait HasHttpRequests.
 *
 * @author overtrue <i@overtrue.me>
 */
trait HasHttpRequests
{
    use ResponseCastable;

    /**
     * @var \GuzzleHttp\ClientInterface
     */
    protected $httpClient;

    /**
     * @var array
     */
    protected $middlewares = [];

    /**
     * @var \GuzzleHttp\HandlerStack
     */
    protected $handlerStack;

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
            $this->httpClient = new Client();
        }

        return $this->httpClient;
    }

    /**
     * Add a middleware.
     *
     * @param callable    $middleware
     * @param string|null $name
     *
     * @return $this
     */
    public function pushMiddleware(callable $middleware, string $name = null)
    {
        if (!is_null($name)) {
            $this->middlewares[$name] = $middleware;
        } else {
            array_push($this->middlewares, $middleware);
        }

        return $this;
    }

    /**
     * Return all middlewares.
     *
     * @return array
     */
    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }

    /**
     * @param array $middlewares
     *
     * @return \GuzzleHttp\Client
     */
    public function setMiddlewares(array $middlewares): Client
    {
        $this->middlewares = $middlewares;

        return $this;
    }

    /**
     * Make a request.
     *
     * @param string $uri
     * @param string $method
     * @param array  $options
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return \Psr\Http\Message\ResponseInterface|\Overtrue\Http\Support\Collection|array|object|string
     */
    public function request($uri, $method = 'GET', $options = []): ResponseInterface
    {
        $method = strtoupper($method);

        $options = array_merge(self::$defaults, $options, ['handler' => $this->getHandlerStack()]);

        $response = $this->getHttpClient()->request($method, $uri, $options);
        $response->getBody()->rewind();

        return $response;
    }

    /**
     * @param \GuzzleHttp\HandlerStack $handlerStack
     *
     * @return $this
     */
    public function setHandlerStack(HandlerStack $handlerStack)
    {
        $this->handlerStack = $handlerStack;

        return $this;
    }

    /**
     * Build a handler stack.
     *
     * @return \GuzzleHttp\HandlerStack
     */
    public function getHandlerStack(): HandlerStack
    {
        if ($this->handlerStack) {
            return $this->handlerStack;
        }

        $this->handlerStack = HandlerStack::create();

        foreach ($this->middlewares as $name => $middleware) {
            $this->handlerStack->push($middleware, $name);
        }

        return $this->handlerStack;
    }
}
