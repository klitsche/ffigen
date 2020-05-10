<?php

declare(strict_types=1);

namespace Klitsche\FFIGen;

use Klitsche\FFIGen\Types\Function_;

class MethodsCollection implements \IteratorAggregate, \Countable
{
    private TypesCollection $types;
    private array $exclude;

    public function __construct(TypesCollection $types, array $exclude = [])
    {
        $this->types = $types;
        $this->exclude = $exclude;
    }

    /**
     * @return \Generator|\Traversable|Method[]
     */
    public function getIterator()
    {
        yield from $this->getMethods();
    }

    /**
     * @return iterable|Method[]
     */
    private function getMethods(): iterable
    {
        foreach ($this->types as $type) {
            if ($type instanceof Function_) {
                if ($this->isExcluded($type->getName())) {
                    continue;
                }
                yield from $this->getMethod($type);
            }
        }
    }

    private function isExcluded(string $name)
    {
        foreach ($this->exclude as $pattern) {
            if (preg_match($pattern, $name) > 0) {
                return true;
            }
        }
        return false;
    }

    private function getMethod(Function_ $type)
    {
        $params = [];
        foreach ($type->getParams() as $name => $paramType) {
            $params[] = new MethodParameter($paramType, $name, $paramType->getCType());
        }
        if ($type->isVariadic()) {
            $params[] = new MethodParameter(null, 'args', '', true);
        }

        $return = new MethodReturnParameter($type->getReturn(), $type->getReturn()->getCType());

        yield $type->getName() => new Method($type->getName(), $params, $return, '');
    }

    public function count()
    {
        return iterator_count($this->getIterator());
    }
}
