<?php

namespace Overtrue\Http\Traits;

use Overtrue\Http\Exceptions\InvalidArgumentException;
use Overtrue\Http\Responses\Response;
use Overtrue\Http\Support\Collection;
use Psr\Http\Message\ResponseInterface;

trait ResponseCastable
{
    protected function castResponseToType(ResponseInterface $response, $type = null)
    {
        if ('raw' === $type) {
            return $response;
        }

        $response = Response::buildFromPsrResponse($response);
        $response->getBody()->rewind();

        switch ($type ?? 'array') {
            case 'collection':
                return $response->toCollection();
            case 'array':
                return $response->toArray();
            case 'object':
                return $response->toObject();
        }
    }

    /**
     * @throws \Overtrue\Http\Exceptions\InvalidArgumentException
     */
    protected function detectAndCastResponseToType($response, $type = null)
    {
        $response = match (true) {
            $response instanceof ResponseInterface => Response::buildFromPsrResponse($response),
            ($response instanceof Collection) || is_array($response) || is_object($response) => new Response(
                200,
                [],
                json_encode($response)
            ),
            is_scalar($response) => new Response(200, [], $response),
            default => throw new InvalidArgumentException(sprintf(
                'Unsupported response type "%s"',
                gettype($response)
            )),
        };

        return $this->castResponseToType($response, $type);
    }
}
