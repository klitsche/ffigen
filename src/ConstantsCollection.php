<?php

declare(strict_types=1);

namespace Klitsche\FFIGen;

use function iter\rewindable\filter;

use Klitsche\FFIGen\Types\Enum;

class ConstantsCollection implements \IteratorAggregate
{
    private DefinesCollection $defines;
    private TypesCollection $types;
    private array $exclude;

    /**
     * @var Constant[]
     */
    private array $additionalConstants = [];

    public function __construct(DefinesCollection $defines, TypesCollection $types, array $exclude = [])
    {
        $this->defines = $defines;
        $this->types = $types;
        $this->exclude = $exclude;
        $this->additionalConstants = [];
    }

    public function add(Constant $constant): void
    {
        $this->additionalConstants[] = $constant;
    }

    public function getIterator()
    {
        return filter(
            $this->getExcludeFilter(),
            $this->getConstants()
        );
    }

    private function getExcludeFilter(): \Closure
    {
        $exclude = $this->exclude;

        return \Closure::fromCallable(
            function (Constant $constant) use ($exclude) {
                foreach ($exclude as $pattern) {
                    if (preg_match($pattern, $constant->getName())) {
                        return false;
                    }
                }

                return true;
            }
        );
    }

    /**
     * @return iterable|Constant[]
     */
    private function getConstants(): iterable
    {
        yield from $this->getDefinesAsConstants();
        yield from $this->getEnumsAsConstants();
        yield from $this->getAdditionalConstants();
    }

    private function getDefinesAsConstants(): iterable
    {
        foreach ($this->defines as $define) {
            yield $define->getName() => new Constant($define->getName(), $define->getValue(), '#define');
        }
    }

    private function getEnumsAsConstants(): iterable
    {
        foreach ($this->types as $type) {
            if ($type instanceof Enum) {
                foreach ($type->getValues() as $name => $value) {
                    yield $name => new Constant($name, $value, 'enum ' . $type->getName());
                }
            }
        }
//        yield from new \EmptyIterator();
        yield from [];
    }

    private function getAdditionalConstants(): iterable
    {
        foreach ($this->additionalConstants as $constant) {
            yield $constant->getName() => $constant;
        }

//        yield from new \EmptyIterator();
        yield from [];
    }
}
