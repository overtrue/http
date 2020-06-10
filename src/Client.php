<?php

/*
 * This file is part of the overtrue/http.
 *
 * (c) overtrue <i@overtrue.me>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Overtrue\Http;

use GuzzleHttp\ClientInterface;
use Overtrue\Http\Traits\CreatesDefaultHttpClient;
use Overtrue\Http\Traits\HasHttpRequests;
use Overtrue\Http\Traits\ResponseCastable;

/**
 * Class BaseClient.
 */
class Client
{
    use HasHttpRequests {
        request as performRequest;
    }
    use CreatesDefaultHttpClient;
    use ResponseCastable;

    /**
     * @var \Overtrue\Http\Config
     */
    protected $config;

    /**
     * @var
     */
    protected $baseUri;

    /**
     * @return static
     */
    public static function create(): self
    {
        return new static(...func_get_args());
    }

    /**
     * @param \Overtrue\Http\Config|array $config
     */
    public function __construct($config = [])
    {
        $this->config = $this->normalizeConfig($config);
    }

    /**
     * @param string $uri
     * @param array  $options
     * @param bool   $async
     *
     * @return \Psr\Http\Message\ResponseInterface|\Overtrue\Http\Support\Collection|array|object|string
     */
    public function get(string $uri, array $options = [], $async = false)
    {
        return $this->request($uri, 'GET', $options, $async);
    }

    /**
     * @param string $uri
     * @param array  $options
     *
     * @return array|object|\Overtrue\Http\Support\Collection|\Psr\Http\Message\ResponseInterface|string
     */
    public function getAsync(string $uri, array $options = [])
    {
        return $this->get($uri, $options, true);
    }

    /**
     * @param string $uri
     * @param array  $data
     * @param array  $options
     * @param bool   $async
     *
     * @return \Psr\Http\Message\ResponseInterface|\Overtrue\Http\Support\Collection|array|object|string
     */
    public function post(string $uri, array $data = [], array $options = [], $async = false)
    {
        return $this->request($uri, 'POST', \array_merge($options, ['form_params' => $data]), $async);
    }

    /**
     * @param string $uri
     * @param array  $data
     * @param array  $options
     *
     * @return array|object|\Overtrue\Http\Support\Collection|\Psr\Http\Message\ResponseInterface|string
     */
    public function postAsync(string $uri, array $data = [], array $options = [])
    {
        return $this->post($uri, $data, $options, true);
    }

    /**
     * @param string $uri
     * @param array  $data
     * @param array  $options
     * @param bool   $async
     *
     * @return \Psr\Http\Message\ResponseInterface|\Overtrue\Http\Support\Collection|array|object|string
     */
    public function patch(string $uri, array $data = [], array $options = [], $async = false)
    {
        return $this->request($uri, 'PATCH', \array_merge($options, ['form_params' => $data]), $async);
    }

    /**
     * @param string $uri
     * @param array  $data
     * @param array  $options
     *
     * @return array|object|\Overtrue\Http\Support\Collection|\Psr\Http\Message\ResponseInterface|string
     */
    public function patchAsync(string $uri, array $data = [], array $options = [])
    {
        return $this->patch($uri, $data, $options, true);
    }

    /**
     * @param string $uri
     * @param array  $data
     * @param array  $options
     * @param bool   $async
     *
     * @return \Psr\Http\Message\ResponseInterface|\Overtrue\Http\Support\Collection|array|object|string
     */
    public function put(string $uri, array $data = [], array $options = [], $async = false)
    {
        return $this->request($uri, 'PUT', \array_merge($options, ['form_params' => $data]), $async);
    }

    /**
     * @param string $uri
     * @param array  $data
     * @param array  $options
     *
     * @return array|object|\Overtrue\Http\Support\Collection|\Psr\Http\Message\ResponseInterface|string
     */
    public function putAsync(string $uri, array $data = [], array $options = [])
    {
        return $this->put($uri, $data, $options, true);
    }

    /**
     * @param string $uri
     * @param array  $options
     * @param bool   $async
     *
     * @return \Psr\Http\Message\ResponseInterface|\Overtrue\Http\Support\Collection|array|object|string
     */
    public function options(string $uri, array $options = [], $async = false)
    {
        return $this->request($uri, 'OPTIONS', $options, $async);
    }

    /**
     * @param string $uri
     * @param array  $options
     *
     * @return array|object|\Overtrue\Http\Support\Collection|\Psr\Http\Message\ResponseInterface|string
     */
    public function optionsAsync(string $uri, array $options = [])
    {
        return $this->options($uri, $options, true);
    }

    /**
     * @param string $uri
     * @param array  $options
     * @param bool   $async
     *
     * @return \Psr\Http\Message\ResponseInterface|\Overtrue\Http\Support\Collection|array|object|string
     */
    public function head(string $uri, array $options = [], $async = false)
    {
        return $this->request($uri, 'HEAD', $options, $async);
    }

    /**
     * @param string $uri
     * @param array  $options
     *
     * @return array|object|\Overtrue\Http\Support\Collection|\Psr\Http\Message\ResponseInterface|string
     */
    public function headAsync(string $uri, array $options = [])
    {
        return $this->head($uri, $options, true);
    }

    /**
     * @param string $uri
     * @param array  $options
     * @param bool   $async
     *
     * @return \Psr\Http\Message\ResponseInterface|\Overtrue\Http\Support\Collection|array|object|string
     */
    public function delete(string $uri, array $options = [], $async = false)
    {
        return $this->request($uri, 'DELETE', $options, $async);
    }

    /**
     * @param string $uri
     * @param array  $options
     *
     * @return array|object|\Overtrue\Http\Support\Collection|\Psr\Http\Message\ResponseInterface|string
     */
    public function deleteAsync(string $uri, array $options = [])
    {
        return $this->delete($uri, $options, true);
    }

    /**
     * @param string $uri
     * @param array  $files
     * @param array  $form
     * @param array  $options
     * @param bool   $async
     *
     * @return \Psr\Http\Message\ResponseInterface|\Overtrue\Http\Support\Collection|array|object|string
     */
    public function upload(string $uri, array $files = [], array $form = [], array $options = [], $async = false)
    {
        $multipart = [];

        foreach ($files as $name => $contents) {
            $contents = \is_resource($contents) ?: \fopen($contents, 'r');
            $multipart[] = \compact('name', 'contents');
        }

        foreach ($form as $name => $contents) {
            $multipart = array_merge($multipart, $this->normalizeMultipartField($name, $contents));
        }

        return $this->request($uri, 'POST', \array_merge($options, ['multipart' => $multipart]), $async);
    }

    /**
     * @param string $uri
     * @param array  $files
     * @param array  $form
     * @param array  $options
     *
     * @return array|object|\Overtrue\Http\Support\Collection|\Psr\Http\Message\ResponseInterface|string
     */
    public function uploadAsync(string $uri, array $files = [], array $form = [], array $options = [])
    {
        return $this->upload($uri, $files, $form, $options, true);
    }

    /**
     * @param string $uri
     * @param string $method
     * @param array  $options
     * @param bool   $async
     *
     * @return \Psr\Http\Message\ResponseInterface|\GuzzleHttp\Promise\PromiseInterface|\Overtrue\Http\Support\Collection|array|object|string
     */
    public function request(string $uri, string $method = 'GET', array $options = [], bool $async = false)
    {
        $result = $this->requestRaw($uri, $method, $options, $async);

        $transformer = function ($response) {
            return $this->castResponseToType($response, $this->config->getOption('response_type'));
        };

        return $async ? $result->then($transformer) : $transformer($result);
    }

    /**
     * @param string $uri
     * @param string $method
     * @param array  $options
     * @param bool   $async
     *
     * @return \Psr\Http\Message\ResponseInterface|\GuzzleHttp\Promise\PromiseInterface|\Overtrue\Http\Support\Collection|array|object|string
     */
    public function requestRaw(string $uri, string $method = 'GET', array $options = [], bool $async = false)
    {
        if (property_exists($this, 'baseUri') && !is_null($this->baseUri)) {
            $options['base_uri'] = $this->baseUri;
        }

        return $this->performRequest($uri, $method, $options, $async);
    }

    /**
     * @return \GuzzleHttp\ClientInterface
     */
    public function getHttpClient(): ClientInterface
    {
        if (!$this->httpClient) {
            $this->httpClient = $this->createDefaultHttClient($this->config->toArray());
        }

        return $this->httpClient;
    }

    /**
     * @return \Overtrue\Http\Config
     */
    public function getConfig(): \Overtrue\Http\Config
    {
        return $this->config;
    }

    /**
     * @param \Overtrue\Http\Config $config
     *
     * @return \Overtrue\Http\Client
     */
    public function setConfig(\Overtrue\Http\Config $config): self
    {
        $this->config = $config;

        return $this;
    }

    /**
     * @param string $name
     * @param mixed  $contents
     *
     * @return array
     */
    public function normalizeMultipartField(string $name, $contents)
    {
        $field = [];

        if (!is_array($contents)) {
            return [compact('name', 'contents')];
        }

        foreach ($contents as $key => $value) {
            $key = sprintf('%s[%s]', $name, $key);
            $field = array_merge($field, is_array($value) ? $this->normalizeMultipartField($key, $value) : [['name' => $key, 'contents' => $value]]);
        }

        return $field;
    }

    /**
     * @param mixed $config
     *
     * @return \Overtrue\Http\Config
     */
    protected function normalizeConfig($config): \Overtrue\Http\Config
    {
        if (\is_array($config)) {
            $config = new Config($config);
        }

        if (!($config instanceof Config)) {
            throw new \InvalidArgumentException('config must be array or instance of Overtrue\Http\Config.');
        }

        return $config;
    }
}
