<?php

declare(strict_types=1);

namespace Klitsche\FFIGen;

use function iter\filter;

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
    public function getIterator()
    {
        return new \ArrayIterator($this->types);
    }

    public function filter(callable $filter): self
    {
        return new static(...filter($filter, $this->types));
    }

    public function count(): int
    {
        return count($this->types);
    }
}
