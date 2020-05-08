<?php

declare(strict_types=1);

namespace Klitsche\FFIGen;

class DefinesCollection implements \IteratorAggregate, \Countable
{
    /**
     * @var Define[]
     */
    private array $defines;

    public function __construct(Define ...$defines)
    {
        $this->defines = $defines;
    }

    /**
     * @return \ArrayIterator|\Traversable|Define[]
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->defines);
    }

    public function filter(callable $filter): self
    {
        return new static(...\iter\filter($filter, $this->defines));
    }

    public function count(): int
    {
        return count($this->defines);
    }
}
