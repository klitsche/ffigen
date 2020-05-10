<?php

declare(strict_types=1);

namespace Klitsche\FFIGen\Types;

use PHPUnit\Framework\TestCase;

/**
 * @covers \Klitsche\FFIGen\Types\Enum
 */
class EnumTest extends TestCase
{
    public function testGetterWithDeclarationName(): void
    {
        $type = (new Enum('any'))
            ->withDeclarationName('some')
            ->add('ONE', 1)
            ->add('TWO', 2);

        $this->assertSame('some', $type->getName());
        $this->assertSame('some', $type->getDeclarationName());
        $this->assertSame('any', $type->getCName());
        $this->assertSame('some', $type->getCType());
        $this->assertSame('some*', $type->getCType('*'));
        $this->assertSame('int', $type->getPhpTypes());
        $this->assertSame('int', $type->getPhpDocTypes());
        $this->assertSame(['ONE' => 1, 'TWO' => 2], $type->getValues());
    }

    public function testGetterWithoutDeclarationName(): void
    {
        $type = (new Enum('any'))
            ->add('ONE', 1)
            ->add('TWO', 2);

        $this->assertSame('any', $type->getName());
        $this->assertSame('', $type->getDeclarationName());
        $this->assertSame('any', $type->getCName());
        $this->assertSame('enum any', $type->getCType());
        $this->assertSame('enum any*', $type->getCType('*'));
        $this->assertSame('int', $type->getPhpTypes());
        $this->assertSame('int', $type->getPhpDocTypes());
        $this->assertSame(['ONE' => 1, 'TWO' => 2], $type->getValues());
    }
}
