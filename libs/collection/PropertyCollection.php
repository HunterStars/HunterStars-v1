<?php

namespace HS\libs\collection;

use ArrayAccess;
use Countable;
use HS\libs\exception\PropertyNotFoundException;
use Iterator;

class PropertyCollection implements ArrayAccess, Iterator, Countable
{
    private array $items;

    public function __construct(array $items = [])
    {
        $this->items = $items;
    }

    public function GetInnerArray(): array
    {
        return $this->items;
    }

    public function count(): int
    {
        return count($this->items);
    }

    /**
     * @throws PropertyNotFoundException
     */
    public function __get($name)
    {
        if (!isset($this->items[$name]))
            throw new PropertyNotFoundException("Propiedad \"$name\" no definida en array.");

        return $this->items[$name];
    }

    public function __set($name, $value): void
    {
        $this->items[$name] = $value;
    }

    public function __isset($name): bool
    {
        return isset($this->items[$name]);
    }

    public function __unset($name): void
    {
        unset($this->items[$name]);
    }


    #Implementación de interfaces.
    public function offsetExists(mixed $offset): bool
    {
        return $this->__isset($offset);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->__get($offset);
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->__set($offset, $value);
    }

    public function offsetUnset(mixed $offset): void
    {
        $this->__unset($offset);
    }

    public function current(): mixed
    {
        return current($this->items);
    }

    public function next(): void
    {
        next($this->items);
    }

    public function key(): mixed
    {
        return key($this->items);
    }

    public function valid(): bool
    {
        $key = Key($this->items);
        return !is_null($key) && isset($this->items[$key]);
    }

    public function rewind(): void
    {
        reset($this->items);
    }
}