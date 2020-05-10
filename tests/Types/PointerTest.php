<?php

declare(strict_types=1);

namespace Klitsche\FFIGen\Types;

use PHPUnit\Framework\TestCase;

/**
 * @covers \Klitsche\FFIGen\Types\Pointer
 */
class PointerTest extends TestCase
{
    public function testGetterWithNonVoid(): void
    {
        $type = (new Pointer(new Builtin('int')))
            ->withDeclarationName('some');

        $this->assertSame('some', $type->getName());
        $this->assertSame('some', $type->getDeclarationName());
        $this->assertSame('int', $type->getCName());
        $this->assertSame('int*', $type->getCType());
        $this->assertSame('int**', $type->getCType('*'));
        $this->assertSame('?\FFI\CData', $type->getPhpTypes());
        $this->assertSame('\FFI\CData|null', $type->getPhpDocTypes());
    }

    public function testGetterWithVoid(): void
    {
        $type = (new Pointer(new Builtin('void')))
            ->withDeclarationName('some');

        $this->assertSame('some', $type->getName());
        $this->assertSame('some', $type->getDeclarationName());
        $this->assertSame('void', $type->getCName());
        $this->assertSame('void*', $type->getCType());
        $this->assertSame('void**', $type->getCType('*'));
        $this->assertSame('', $type->getPhpTypes());
        $this->assertSame('\FFI\CData|object|string|null', $type->getPhpDocTypes());
    }
}
