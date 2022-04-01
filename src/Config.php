<?php

namespace Overtrue\Http;

class Config
{
    /**
     * @see http://docs.guzzlephp.org/en/latest/request-options.html
     */
    protected array $options = [
        'base_uri' => null,
        'timeout' => 3000,
        'connect_timeout' => 3000,
        'proxy' => [],
    ];

    protected bool $autoTrimEndpointSlash = true;

    public function __construct(array $options = [])
    {
        $this->options = array_merge($this->options, $options);
    }

    public function getBaseUri(): string
    {
        return $this->options['base_uri'] ?? '';
    }

    public function setBaseUri(string $baseUri): static
    {
        $this->options['base_uri'] = $baseUri;

        return $this;
    }

    public function getTimeout(): int
    {
        return $this->options['timeout'] ?? 3000;
    }

    public function setTimeout(float|int $timeout): static
    {
        $this->options['timeout'] = $timeout;

        return $this;
    }

    public function getConnectTimeout(): int
    {
        return $this->options['connect_timeout'] ?? 3000;
    }

    public function setConnectTimeout(float|int $connectTimeout): static
    {
        $this->options['connect_timeout'] = $connectTimeout;

        return $this;
    }

    public function getProxy(): array
    {
        return $this->options['proxy'] ?? [];
    }

    public function setProxy(array $proxy): static
    {
        $this->options['proxy'] = $proxy;

        return $this;
    }

    public function toArray(): array
    {
        return $this->options;
    }

    public function setOption(string $key, mixed $value): static
    {
        $this->options[$key] = $value;

        return $this;
    }

    public function getOption(string $key, mixed $default = null)
    {
        return $this->options[$key] ?? $default;
    }

    public function mergeOptions(array $options): static
    {
        $this->options = array_merge($this->options, $options);

        return $this;
    }

    public function setOptions(array $options): static
    {
        $this->options = $options;

        return $this;
    }

    public function getOptions(): array
    {
        return $this->options;
    }
}
