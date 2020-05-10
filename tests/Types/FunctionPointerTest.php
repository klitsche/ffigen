<?php

declare(strict_types=1);

namespace Klitsche\FFIGen\Types;

use PHPUnit\Framework\TestCase;

/**
 * @covers \Klitsche\FFIGen\Types\FunctionPointer
 */
class FunctionPointerTest extends TestCase
{
    public function testGetterWithoutDeclarationName()
    {
        $function = (new Function_(
            new Builtin('void'),
            [
                'param1' => new Builtin('int'),
                'param2' => new CharPointer(new Builtin('char')),
            ],
            true
        ))->withDeclarationName('some');

        $type = (new FunctionPointer($function))
            ->withConst(true);

        $this->assertSame('some', $type->getName());
        $this->assertSame('some', $type->getDeclarationName());
        $this->assertSame('', $type->getCName());
        $this->assertSame('void(some*)(int, char*, ...)', $type->getCType());
        $this->assertSame('void(some**)(int, char*, ...)', $type->getCType('*'));
        $this->assertSame('', $type->getPhpTypes());
        $this->assertSame('\FFI\CData|\Closure', $type->getPhpDocTypes());
    }

    public function testGetterWithDeclarationName()
    {
        $function = (new Function_(
            new Builtin('void'),
            [
                'param1' => new Builtin('int'),
                'param2' => new CharPointer(new Builtin('char')),
            ],
            true
        ));

        $type = (new FunctionPointer($function))
            ->withDeclarationName('some')
            ->withConst(true);

        $this->assertSame('some', $type->getName());
        $this->assertSame('some', $type->getDeclarationName());
        $this->assertSame('', $type->getCName());
        $this->assertSame('void(some*)(int, char*, ...)', $type->getCType());
        $this->assertSame('void(some**)(int, char*, ...)', $type->getCType('*'));
        $this->assertSame('', $type->getPhpTypes());
        $this->assertSame('\FFI\CData|\Closure', $type->getPhpDocTypes());
    }
}
