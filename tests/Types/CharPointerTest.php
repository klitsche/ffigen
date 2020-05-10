<?php

declare(strict_types=1);

namespace Klitsche\FFIGen\Types;

use PHPUnit\Framework\TestCase;

/**
 * @covers \Klitsche\FFIGen\Types\CharPointer
 * @covers \Klitsche\FFIGen\Types\Type
 */
class CharPointerTest extends TestCase
{
    public function testGetterWithConst(): void
    {
        $type = (new CharPointer(new Builtin('char')))
            ->withDeclarationName('some')
            ->withConst(true);

        $this->assertSame('some', $type->getName());
        $this->assertSame('some', $type->getDeclarationName());
        $this->assertSame('char', $type->getCName());
        $this->assertSame('const char*', $type->getCType());
        $this->assertSame('const char**', $type->getCType('*'));
        $this->assertSame('?string', $type->getPhpTypes());
        $this->assertSame('string|null', $type->getPhpDocTypes());
        $this->assertTrue($type->isConst());
    }

    public function testGetterWithoutConst(): void
    {
        $type = (new CharPointer(new Builtin('char')))
            ->withDeclarationName('some');

        $this->assertSame('some', $type->getName());
        $this->assertSame('some', $type->getDeclarationName());
        $this->assertSame('char', $type->getCName());
        $this->assertSame('char*', $type->getCType());
        $this->assertSame('char**', $type->getCType('*'));
        $this->assertSame('?\FFI\CData', $type->getPhpTypes());
        $this->assertSame('\FFI\CData|null', $type->getPhpDocTypes());
        $this->assertFalse($type->isConst());
    }
}
