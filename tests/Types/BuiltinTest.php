<?php

declare(strict_types=1);

namespace Klitsche\FFIGen\Types;

use PHPUnit\Framework\TestCase;

/**
 * @covers \Klitsche\FFIGen\Types\Builtin
 */
class BuiltinTest extends TestCase
{
    public function testGetterWithInt(): void
    {
        $type = (new Builtin('int16_t'))
            ->withDeclarationName('some');

        $this->assertSame('some', $type->getName());
        $this->assertSame('some', $type->getDeclarationName());
        $this->assertSame('int16_t', $type->getCName());
        $this->assertSame('some', $type->getCType());
        $this->assertSame('?int', $type->getPhpTypes());
        $this->assertSame('int|null', $type->getPhpDocTypes());
    }

    public function testGetterWithFloat(): void
    {
        $type = (new Builtin('long double'))
            ->withDeclarationName('some');

        $this->assertSame('some', $type->getName());
        $this->assertSame('some', $type->getDeclarationName());
        $this->assertSame('long double', $type->getCName());
        $this->assertSame('some', $type->getCType());
        $this->assertSame('?float', $type->getPhpTypes());
        $this->assertSame('float|null', $type->getPhpDocTypes());
    }

    public function testGetterWithChar(): void
    {
        $type = (new Builtin('unsigned char'))
            ->withDeclarationName('some');

        $this->assertSame('some', $type->getName());
        $this->assertSame('some', $type->getDeclarationName());
        $this->assertSame('unsigned char', $type->getCName());
        $this->assertSame('some', $type->getCType());
        $this->assertSame('?int', $type->getPhpTypes());
        $this->assertSame('int|null', $type->getPhpDocTypes());
    }

    public function testGetterWithVoid(): void
    {
        $type = new Builtin('void');

        $this->assertSame('void', $type->getName());
        $this->assertSame('', $type->getDeclarationName());
        $this->assertSame('void', $type->getCName());
        $this->assertSame('void', $type->getCType());
        $this->assertSame('void', $type->getPhpTypes());
        $this->assertSame('void', $type->getPhpDocTypes());
    }

    public function testNotMappableCType(): void
    {
        $this->assertFalse(Builtin::isMappable('nope'));

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessageMatches('/nope/');
        new Builtin('nope');
    }
}
