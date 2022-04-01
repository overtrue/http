<?php

namespace Overtrue\Http;

use GuzzleHttp\ClientInterface;
use Overtrue\Http\Traits\CreatesDefaultHttpClient;
use Overtrue\Http\Traits\HasHttpRequests;
use Overtrue\Http\Traits\ResponseCastable;

class Client
{
    use HasHttpRequests {
        request as performRequest;
    }
    use CreatesDefaultHttpClient;
    use ResponseCastable;

    protected Config $config;
    protected ?string $baseUri = null;

    public static function create(): static
    {
        return new static(...func_get_args());
    }

    public function __construct(Config|array $config = [])
    {
        $this->config = $this->normalizeConfig($config);
    }

    public function get(string $uri, array $options = [], bool $async = false)
    {
        return $this->request($uri, 'GET', $options, $async);
    }

    public function getAsync(string $uri, array $options = [])
    {
        return $this->get($uri, $options, true);
    }

    public function post(string $uri, array $data = [], array $options = [], bool $async = false)
    {
        return $this->request($uri, 'POST', \array_merge($options, ['form_params' => $data]), $async);
    }

    public function postAsync(string $uri, array $data = [], array $options = [])
    {
        return $this->post($uri, $data, $options, true);
    }

    public function patch(string $uri, array $data = [], array $options = [], bool $async = false)
    {
        return $this->request($uri, 'PATCH', \array_merge($options, ['form_params' => $data]), $async);
    }

    public function patchAsync(string $uri, array $data = [], array $options = [])
    {
        return $this->patch($uri, $data, $options, true);
    }

    public function put(string $uri, array $data = [], array $options = [], bool $async = false)
    {
        return $this->request($uri, 'PUT', \array_merge($options, ['form_params' => $data]), $async);
    }

    public function putAsync(string $uri, array $data = [], array $options = [])
    {
        return $this->put($uri, $data, $options, true);
    }

    public function options(string $uri, array $options = [], bool $async = false)
    {
        return $this->request($uri, 'OPTIONS', $options, $async);
    }

    public function optionsAsync(string $uri, array $options = [])
    {
        return $this->options($uri, $options, true);
    }

    public function head(string $uri, array $options = [], bool $async = false)
    {
        return $this->request($uri, 'HEAD', $options, $async);
    }

    public function headAsync(string $uri, array $options = [])
    {
        return $this->head($uri, $options, true);
    }

    public function delete(string $uri, array $options = [], bool $async = false)
    {
        return $this->request($uri, 'DELETE', $options, $async);
    }

    public function deleteAsync(string $uri, array $options = [])
    {
        return $this->delete($uri, $options, true);
    }

    public function upload(string $uri, array $files = [], array $form = [], array $options = [], bool $async = false)
    {
        $multipart = [];

        foreach ($files as $name => $contents) {
            $contents = \is_resource($contents) ? $contents : \fopen($contents, 'r');
            $multipart[] = \compact('name', 'contents');
        }

        foreach ($form as $name => $contents) {
            $multipart = array_merge($multipart, $this->normalizeMultipartField($name, $contents));
        }

        return $this->request($uri, 'POST', \array_merge($options, ['multipart' => $multipart]), $async);
    }

    public function uploadAsync(string $uri, array $files = [], array $form = [], array $options = [])
    {
        return $this->upload($uri, $files, $form, $options, true);
    }

    public function request(string $uri, string $method = 'GET', array $options = [], bool $async = false)
    {
        $result = $this->requestRaw($uri, $method, $options, $async);

        $transformer = function ($response) {
            return $this->castResponseToType($response, $this->config->getOption('response_type'));
        };

        return $async ? $result->then($transformer) : $transformer($result);
    }

    public function requestRaw(string $uri, string $method = 'GET', array $options = [], bool $async = false)
    {
        if ($this->baseUri) {
            $options['base_uri'] = $this->baseUri;
        }

        return $this->performRequest($uri, $method, $options, $async);
    }

    public function getHttpClient(): ClientInterface
    {
        if (!$this->httpClient) {
            $this->httpClient = $this->createDefaultHttClient($this->config->toArray());
        }

        return $this->httpClient;
    }

    public function getConfig(): Config
    {
        return $this->config;
    }

    public function setConfig(Config $config): static
    {
        $this->config = $config;

        return $this;
    }

    public function normalizeMultipartField(string $name, mixed $contents): array
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

    protected function normalizeConfig(array|Config $config): Config
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
