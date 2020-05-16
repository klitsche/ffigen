<?php

declare(strict_types=1);

namespace Klitsche\FFIGen;

use Klitsche\FFIGen\Types\Builtin;
use Klitsche\FFIGen\Types\Function_;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Klitsche\FFIGen\MethodsCollection
 */
class MethodsCollectionTest extends TestCase
{
    public function testFilter(): void
    {
        $collection = new MethodsCollection(
            new TypesCollection(
                (new Function_(
                    new Builtin('void'),
                    [
                        'param1' => new Builtin('int'),
                    ],
                    true
                ))->withDeclarationName('func1'),
                (new Function_(
                    new Builtin('float'),
                    [
                        'param1' => new Builtin('int'),
                    ],
                    false
                ))->withDeclarationName('func2'),
                (new Function_(
                    new Builtin('int'),
                    [
                        'param1' => new Builtin('int'),
                    ],
                    false
                ))->withDeclarationName('func3'),
            ),
            ['/func(2|4)$/']
        );
        $collection->add(new Method('func4', [], null, ''));
        $collection->add(new Method('func5', [], null, ''));

        $this->assertCount(3, $collection);

        $methods = [];
        foreach ($collection as $name => $method) {
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
        $collection = new MethodsCollection(new TypesCollection());

        $this->assertCount(0, $collection);
    }
}
