<?php

declare(strict_types=1);

namespace Klitsche\FFIGen;

use PHPUnit\Framework\TestCase;

/**
 * @covers \Klitsche\FFIGen\MethodsCollection
 */
class MethodsCollectionTest extends TestCase
{
    public function testFilter(): void
    {
        $collection = new MethodsCollection(
            new Method('func1', [], null, ''),
            new Method('func2', [], null, ''),
            new Method('func3', [], null, ''),
        );
        $collection->add(new Method('func4', [], null, ''));
        $collection->add(new Method('func5', [], null, ''));

        $this->assertCount(5, $collection);

        $filteredCollections = $collection->filter(['/func(2|4)$/']);

        $this->assertCount(3, $filteredCollections);
        $methods = [];
        foreach ($filteredCollections as $name => $method) {
            $methods[$name] = $method->getName();
        }
        $this->assertSame(
            [
                'func1' => 'func1',
                'func3' => 'func3',
                'func5' => 'func5',
            ],
            $methods
        );
    }

    public function testEmpty(): void
    {
        $collection = new MethodsCollection();

        $this->assertCount(0, $collection);
    }
}
