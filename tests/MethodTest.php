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
        $method->getDocBlock()->addTag(new DocBlockTag('since', 'some version'));

        $this->assertSame(
            <<<PHPCODE
                /**
                 * some desc4
                 * @param float|null \$param1 some desc1
                 * @param int|null ...\$param2 some desc2
                 * @return int|null some desc3
                 * @since some version
                 */
                public static function func1(?float \$param1, ?int ...\$param2): ?int
                {
                    return static::getFFI()->func1(\$param1, ...\$param2);
                }
            PHPCODE,
            $method->print('    ')
        );
    }

    public function testGetPhpCodeWithVoidParamAndReturn(): void
    {
        $method = new Method(
            'func1',
            [
                new MethodParameter(new Builtin('void'), 'param1', 'some desc1', false),
            ],
            new MethodReturnParameter(new Builtin('void'), 'some desc3'),
            'some desc4'
        );
        $method->getDocBlock()->addTag(new DocBlockTag('since', 'some version'));

        $this->assertSame(
            <<<PHPCODE
                /**
                 * some desc4
                 * @since some version
                 */
                public static function func1(): void
                {
                    static::getFFI()->func1();
                }
            PHPCODE,
            $method->print('    ')
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
}
