<?php

declare(strict_types=1);

namespace Klitsche\FFIGen;

use Klitsche\FFIGen\Types\Builtin;
use Klitsche\FFIGen\Types\Function_;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Klitsche\FFIGen\MethodsCollector
 */
class MethodsCollectorTest extends TestCase
{
    public function testCollect(): void
    {
        $collector = new MethodsCollector();
        $collection = $collector->collect(
            new TypesCollection(
                (new Builtin('int'))->withDeclarationName('nofunc'),
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
            )
        );

        $methods = [];
        foreach ($collection as $name => $method) {
            $methods[$name] = $method->getName();
        }
        $this->assertSame(
            [
                'func1' => 'func1',
                'func2' => 'func2',
                'func3' => 'func3',
            ],
            $methods
        );
    }
}
