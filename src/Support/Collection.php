<?php

namespace Overtrue\Http\Support;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;
use JetBrains\PhpStorm\Pure;
use JsonSerializable;

class Collection implements ArrayAccess, Countable, IteratorAggregate, JsonSerializable
{
    protected array $items = [];

    public function __construct(array $items = [])
    {
        foreach ($items as $key => $value) {
            $this->set($key, $value);
        }
    }

    public function all(): array
    {
        return $this->items;
    }

    public function only(array $keys): self
    {
        $return = [];

        foreach ($keys as $key) {
            $value = $this->get($key);

            if (!is_null($value)) {
                $return[$key] = $value;
            }
        }

        return new static($return);
    }

    public function except(string|array $keys): self
    {
        $keys = is_array($keys) ? $keys : func_get_args();

        return new static(array_diff($this->items, array_combine($keys, array_pad([], count($keys), null))));
    }

    public function merge(array|Collection $items): self
    {
        foreach ($items as $key => $value) {
            $this->set($key, $value);
        }

        return new static($this->all());
    }

    public function has(string $key): bool
    {
        return !is_null($this->dotGet($this->items, $key));
    }

    public function first(): mixed
    {
        return reset($this->items);
    }

    public function last(): mixed
    {
        $end = end($this->items);

        reset($this->items);

        return $end;
    }

    public function add(string $key, mixed $value)
    {
        $this->dotSet($this->items, $key, $value);
    }

    public function set(string $key, mixed $value)
    {
        $this->dotSet($this->items, $key, $value);
    }

    public function forget(string $key)
    {
        $this->dotRemove($this->items, $key);
    }

    public function get(string $key, mixed $default = null)
    {
        return $this->dotGet($this->items, $key, $default);
    }

    public function dotGet(array $array, string $key, mixed $default = null)
    {
        if (array_key_exists($key, $array)) {
            return $array[$key];
        }
        foreach (explode('.', $key) as $segment) {
            if (array_key_exists($segment, $array)) {
                $array = $array[$segment];
            } else {
                return $default;
            }
        }
    }

    public function dotSet(array &$array, string $key, mixed $value): array
    {
        $keys = explode('.', $key);
        while (count($keys) > 1) {
            $key = array_shift($keys);
            if (!isset($array[$key]) || !is_array($array[$key])) {
                $array[$key] = [];
            }
            $array = &$array[$key];
        }
        $array[array_shift($keys)] = $value;

        return $array;
    }

    public function dotRemove(array &$array, array|string $keys)
    {
        $original = &$array;
        $keys = (array) $keys;
        if (0 === count($keys)) {
            return;
        }

        foreach ($keys as $key) {
            if (array_key_exists($key, $array)) {
                unset($array[$key]);
                continue;
            }
            $parts = explode('.', $key);

            $array = &$original;

            while (count($parts) > 1) {
                $part = array_shift($parts);
                if (isset($array[$part]) && is_array($array[$part])) {
                    $array = &$array[$part];
                } else {
                    continue 2;
                }
            }
            unset($array[array_shift($parts)]);
        }
    }

    #[Pure]
    public function toArray(): array
    {
        return $this->all();
    }

    public function toJson(int $option = JSON_UNESCAPED_UNICODE): string
    {
        return json_encode($this->all(), $option);
    }

    public function __toString(): string
    {
        return $this->toJson();
    }

    public function jsonSerialize(): array
    {
        return $this->items;
    }

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->items);
    }

    public function count(): int
    {
        return count($this->items);
    }

    public function __get($key)
    {
        return $this->get($key);
    }

    public function __set($key, $value)
    {
        $this->set($key, $value);
    }

    public function __isset($key): bool
    {
        return $this->has($key);
    }

    public function __unset($key)
    {
        $this->forget($key);
    }

    public static function __set_state($array): object
    {
        return new self($array);
    }

    public function offsetExists($offset): bool
    {
        return $this->has($offset);
    }

    public function offsetUnset($offset): void
    {
        if ($this->offsetExists($offset)) {
            $this->forget($offset);
        }
    }

    public function offsetGet($offset): mixed
    {
        return $this->offsetExists($offset) ? $this->get($offset) : null;
    }

    public function offsetSet($offset, $value): void
    {
        $this->set($offset, $value);
    }
}
