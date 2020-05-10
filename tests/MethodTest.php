<?php

declare(strict_types=1);

namespace Klitsche\FFIGen;

use Klitsche\FFIGen\Types\Builtin;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Klitsche\FFIGen\Method
 */
class MethodTest extends TestCase
{
    public function testGetPhpCode(): void
    {
        $method = new Method(
            'func1',
            [
                new MethodParameter(new Builtin('float'), 'param1', 'some desc1', false),
                new MethodParameter(new Builtin('int'), 'param2', 'some desc2', true),
            ],
            new MethodReturnParameter(new Builtin('int'), 'some desc3'),
            'some desc4'
        );
        $method->addDocBlockTag('since', 'some version');

        $this->assertSame(
            <<<PHP
                /**
                 * some desc4
                 * @since some version
                 * @param float|null \$param1 some desc1
                 * @param int|null ...\$param2 some desc2
                 * @return int|null some desc3
                 */
                public static function func1(?float \$param1, ?int ...\$param2): ?int
                {
                    return static::getFFI()->func1(\$param1, ...\$param2);
                }
            PHP,
            $method->getPhpCode('    ')
        );
    }

    public function testGetName(): void
    {
        $method = new Method(
            'func1',
            [],
            null,
            ''
        );

        $this->assertSame('func1', $method->getName());
    }

    public function testGetDocBlockTags(): void
    {
        $method = new Method(
            'func1',
            [],
            null,
            ''
        );
        $method->addDocBlockTag('since', 'some version');
        $method->addDocBlockTag('since', 'some other');

        $this->assertSame(
            [
                ['since', 'some version'],
                ['since', 'some other'],
            ],
            $method->getDocBlockTags()
        );
    }
}
