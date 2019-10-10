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

/**
 * Class Config.
 *
 * @author overtrue <i@overtrue.me>
 */
class Config
{
    /**
     * @see http://docs.guzzlephp.org/en/latest/request-options.html
     *
     * @var array
     */
    protected $options = [
        'base_uri'        => null,
        'timeout'         => 3000,
        'connect_timeout' => 3000,
        'proxy'           => [],
    ];

    /**
     * @var bool
     */
    protected $autoTrimEndpointSlash = true;

    /**
     * Config constructor.
     *
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $this->options = array_merge($this->options, $options);
    }

    /**
     * @return string
     */
    public function getBaseUri(): string
    {
        return $this->options['base_uri'] ?? '';
    }

    /**
     * @param string $baseUri
     *
     * @return \Overtrue\Http\Config
     */
    public function setBaseUri($baseUri): self
    {
        $this->options['base_uri'] = $baseUri;

        return $this;
    }

    /**
     * @return int
     */
    public function getTimeout(): int
    {
        return $this->options['timeout'] ?? 3000;
    }

    /**
     * @param int $timeout
     *
     * @return \Overtrue\Http\Config
     */
    public function setTimeout($timeout): self
    {
        $this->options['timeout'] = $timeout;

        return $this;
    }

    /**
     * @return int
     */
    public function getConnectTimeout(): int
    {
        return $this->options['connect_timeout'] ?? 3000;
    }

    /**
     * @param int $connectTimeout
     *
     * @return \Overtrue\Http\Config
     */
    public function setConnectTimeout($connectTimeout): self
    {
        $this->options['connect_timeout'] = $connectTimeout;

        return $this;
    }

    /**
     * @return array
     */
    public function getProxy(): array
    {
        return $this->options['proxy'] ?? [];
    }

    /**
     * @param array $proxy
     *
     * @return \Overtrue\Http\Config
     */
    public function setProxy(array $proxy): self
    {
        $this->options['proxy'] = $proxy;

        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->options;
    }

    /**
     * @param string $key
     * @param mixed  $value
     *
     * @return $this
     */
    public function setOption($key, $value): self
    {
        $this->options[$key] = $value;

        return $this;
    }

    /**
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    public function getOption($key, $default = null)
    {
        return $this->options[$key] ?? $default;
    }

    /**
     * @param array $options
     *
     * @return $this
     */
    public function mergeOptions(array $options): self
    {
        $this->options = array_merge($this->options, $options);

        return $this;
    }

    /**
     * @param array $options
     *
     * @return $this
     */
    public function setOptions(array $options): self
    {
        $this->options = $options;

        return $this;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @return bool
     */
    public function needAutoTrimEndpointSlash(): bool
    {
        return $this->autoTrimEndpointSlash;
    }

    /**
     * @return $this
     */
    public function disableAutoTrimEndpointSlash(): self
    {
        $this->autoTrimEndpointSlash = false;

        return $this;
    }
}
