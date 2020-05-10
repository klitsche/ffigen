<?php

declare(strict_types=1);

namespace Klitsche\FFIGen\Types;

use PHPUnit\Framework\TestCase;

/**
 * @covers \Klitsche\FFIGen\Types\Function_
 */
class FunctionTest extends TestCase
{
    public function testGetterWithoutDeclarationName(): void
    {
        $type = (new Function_(
            $return = new Builtin('void'),
            $params = [
                'param1' => new Builtin('int'),
                'param2' => new CharPointer(new Builtin('char')),
            ],
            true,
        ));

        $this->assertSame('', $type->getName());
        $this->assertSame('', $type->getDeclarationName());
        $this->assertSame('', $type->getCName());
        $this->assertSame('void()(int, char*, ...)', $type->getCType());
        $this->assertSame('void(*)(int, char*, ...)', $type->getCType('*'));
        $this->assertSame('', $type->getPhpTypes());
        $this->assertSame('\FFI\CData|\Closure', $type->getPhpDocTypes());
        $this->assertSame($return, $type->getReturn());
        $this->assertSame($params, $type->getParams());
    }

    public function testGetterWithDeclarationName(): void
    {
        $type = (new Function_(
            $return = new Builtin('void'),
            $params = [
                'param1' => new Builtin('int'),
                'param2' => new CharPointer(new Builtin('char')),
            ],
            true,
        ))->withDeclarationName('some');

        $this->assertSame('some', $type->getName());
        $this->assertSame('some', $type->getDeclarationName());
        $this->assertSame('', $type->getCName());
        $this->assertSame('void(some)(int, char*, ...)', $type->getCType());
        $this->assertSame('void(some*)(int, char*, ...)', $type->getCType('*'));
        $this->assertSame('', $type->getPhpTypes());
        $this->assertSame('\FFI\CData|\Closure', $type->getPhpDocTypes());
        $this->assertSame($return, $type->getReturn());
        $this->assertSame($params, $type->getParams());
    }
}
