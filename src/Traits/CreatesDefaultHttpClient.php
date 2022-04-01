<?php

namespace Overtrue\Http\Traits;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;

trait CreatesDefaultHttpClient
{
    protected array $middlewares = [];
    protected ?HandlerStack $handlerStack = null;

    public function createDefaultHttClient(array $options): Client
    {
        return new Client(array_merge([
            'handler' => $this->getHandlerStack(),
        ], $options));
    }

    public function pushMiddleware(callable $middleware, string $name = null): static
    {
        if (!is_null($name)) {
            $this->middlewares[$name] = $middleware;
        } else {
            $this->middlewares[] = $middleware;
        }

        return $this;
    }

    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }

    public function setMiddlewares(array $middlewares): static
    {
        $this->middlewares = $middlewares;

        return $this;
    }

    public function setHandlerStack(HandlerStack $handlerStack): static
    {
        $this->handlerStack = $handlerStack;

        return $this;
    }

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
