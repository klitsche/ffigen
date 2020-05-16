<?php

declare(strict_types=1);

namespace Klitsche\FFIGen;

class MethodsCollection implements \IteratorAggregate, \Countable
{
    /**
     * @var Method[]
     */
    private array $methods;

    public function __construct(Method ...$methods)
    {
        $this->methods = [];
        foreach ($methods as $method) {
            $this->methods[$method->getName()] = $method;
        }
    }

    /**
     * @return \ArrayIterator|\Traversable|Method[]
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->methods);
    }

    public function filter(array $exclude)
    {
        $methods = [];
        foreach ($this->methods as $method) {
            if ($this->isExcluded($method->getName(), $exclude)) {
                continue;
            }
            $methods[] = $method;
        }
        return new static(...$methods);
    }

    public function add(Method $method): void
    {
        $this->methods[$method->getName()] = $method;
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

    public function count()
    {
        return count($this->methods);
    }
}
