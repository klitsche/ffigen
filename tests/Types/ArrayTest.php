<?php

declare(strict_types=1);

namespace Klitsche\FFIGen\Types;

use PHPUnit\Framework\TestCase;

/**
 * @covers \Klitsche\FFIGen\Types\Array_
 */
class ArrayTest extends TestCase
{
    public function testGetterWithoutSize(): void
    {
        $type = (new Array_(new Builtin('int')))
            ->withDeclarationName('some');

        $this->assertSame('some', $type->getName());
        $this->assertSame('some', $type->getDeclarationName());
        $this->assertSame('int', $type->getCName());
        $this->assertSame('int[]', $type->getCType());
        $this->assertSame('*(int)[]', $type->getCType('*'));
        $this->assertSame('?\FFI\CData', $type->getPhpTypes());
        $this->assertSame('\FFI\CData|null', $type->getPhpDocTypes());
    }

    public function testGetterWithSize(): void
    {
        $type = (new Array_(new Builtin('int'), 23))
            ->withDeclarationName('some');

        $this->assertSame('some', $type->getName());
        $this->assertSame('some', $type->getDeclarationName());
        $this->assertSame('int', $type->getCName());
        $this->assertSame('int[23]', $type->getCType());
        $this->assertSame('*(int)[23]', $type->getCType('*'));
        $this->assertSame('?\FFI\CData', $type->getPhpTypes());
        $this->assertSame('\FFI\CData|null', $type->getPhpDocTypes());
    }
}
