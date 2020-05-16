<?php

declare(strict_types=1);

namespace Klitsche\FFIGen;

use Klitsche\FFIGen\Types\Enum;

class ConstantsCollection implements \IteratorAggregate, \Countable
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

    /**
     * @return \Iterator|\Traversable|Constant[]
     */
    public function getIterator()
    {
        yield from $this->getDefinesAsConstants();
        yield from $this->getEnumsAsConstants();
        yield from $this->getAdditionalConstants();
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

    private function getDefinesAsConstants(): iterable
    {
        foreach ($this->defines as $define) {
            if ($this->isExcluded($define->getName())) {
                continue;
            }
            yield $define->getName() => new Constant($define->getName(), $define->getValue(), '#define');
        }
    }

    private function getEnumsAsConstants(): iterable
    {
        foreach ($this->types as $type) {
            if ($type instanceof Enum) {
                foreach ($type->getValues() as $name => $value) {
                    if ($this->isExcluded($name)) {
                        continue;
                    }
                    yield $name => new Constant($name, $value, 'enum ' . $type->getName());
                }
            }
        }
    }

    private function getAdditionalConstants(): iterable
    {
        foreach ($this->additionalConstants as $constant) {
            if ($this->isExcluded($constant->getName())) {
                continue;
            }
            yield $constant->getName() => $constant;
        }
    }

    public function count()
    {
        return iterator_count($this->getIterator());
    }
}
