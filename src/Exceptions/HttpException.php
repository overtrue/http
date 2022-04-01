<?php

namespace Overtrue\Http\Exceptions;

use Psr\Http\Message\ResponseInterface;

class HttpException extends Exception
{
    public ?ResponseInterface $response;
    public mixed $formattedResponse;

    public function __construct(string $message, ResponseInterface $response = null, mixed $formattedResponse = null, int $code = null)
    {
        parent::__construct($message, $code);

        $this->response = $response;
        $this->formattedResponse = $formattedResponse;

        $response?->getBody()->rewind();
    }
}
