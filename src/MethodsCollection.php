<?php

declare(strict_types=1);

namespace Klitsche\FFIGen;

use function iter\rewindable\filter;

use Klitsche\FFIGen\Types\Function_;

class MethodsCollection implements \IteratorAggregate
{
    private TypesCollection $types;
    private array $exclude;

    public function __construct(TypesCollection $types, array $exclude = [])
    {
        $this->types = $types;
        $this->exclude = $exclude;
    }

    public function getIterator()
    {
        return filter(
            $this->getExcludeFilter(),
            $this->getMethods()
        );
    }

    private function getExcludeFilter(): \Closure
    {
        $exclude = $this->exclude;

        return \Closure::fromCallable(
            function (Method $method) use ($exclude) {
                foreach ($exclude as $pattern) {
                    if (preg_match($pattern, $method->getName())) {
                        return false;
                    }
                }

                return true;
            }
        );
    }

    /**
     * @return iterable|Method[]
     */
    private function getMethods(): iterable
    {
        foreach ($this->types as $type) {
            if ($type instanceof Function_) {
                $return = new MethodParameter($type->getReturn(), null, $type->getReturn()->getCType());
                $params = [];
                foreach ($type->getParams() as $name => $paramType) {
                    $params[] = new MethodParameter($paramType, $name, $paramType->getCType());
                }
                if ($type->isVariadic()) {
                    $params[] = new MethodParameter(null, 'args', '', true);
                }
                yield $type->getName() => new Method($type->getName(), $params, $return, '');
            }
        }
//        yield from new \EmptyIterator();
        yield from [];
    }
}
