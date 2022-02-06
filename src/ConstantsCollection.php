<?php

declare(strict_types=1);

namespace Klitsche\FFIGen;

class ConstantsCollection implements \IteratorAggregate, \Countable
{
    /**
     * @var Constant[]
     */
    private array $constants = [];

    public function __construct(Constant ...$constants)
    {
        $this->constants = [];
        foreach ($constants as $constant) {
            $this->constants[$constant->getName()] = $constant;
        }
    }

    /**
     * @return \ArrayIterator|\Traversable|Constant[]
     */
    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->constants);
    }

    public function filter(array $exclude)
    {
        $constants = [];
        foreach ($this->constants as $constant) {
            if ($this->isExcluded($constant->getName(), $exclude)) {
                continue;
            }
            $constants[] = $constant;
        }
        return new static(...$constants);
    }

    public function add(Constant $constant): void
    {
        $this->constants[$constant->getName()] = $constant;
    }

    private function isExcluded(string $name, array $exclude)
    {
        foreach ($exclude as $pattern) {
            if (preg_match($pattern, $name) > 0) {
                return true;
            }
        }
        return false;
    }

    public function count(): int
    {
        return iterator_count($this->getIterator());
    }
}
