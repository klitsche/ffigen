<?php

declare(strict_types=1);

namespace Klitsche\FFIGen;

use Klitsche\FFIGen\Types\Type;

class TypesCollection implements \IteratorAggregate, \Countable
{
    /**
     * @var Type[]
     */
    private array $types;

    public function __construct(Type ...$types)
    {
        $this->types = $types;
    }

    /**
     * @return \ArrayIterator|\Traversable|Type[]
     */
    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->types);
    }

    public function count(): int
    {
        return count($this->types);
    }
}
