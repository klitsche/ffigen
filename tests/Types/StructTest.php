<?php

declare(strict_types=1);

namespace Klitsche\FFIGen\Types;

use PHPUnit\Framework\TestCase;

/**
 * @covers \Klitsche\FFIGen\Types\Struct
 */
class StructTest extends TestCase
{
    public function testGetterWithoutDeclarationName()
    {
        $type = (new Struct('any', false))
            ->add('field1', $field1 = new Builtin('int'))
            ->add('field2', $field2 = new Builtin('int'));

        $this->assertSame('any', $type->getName());
        $this->assertSame('', $type->getDeclarationName());
        $this->assertSame('any', $type->getCName());
        $this->assertSame('struct any', $type->getCType());
        $this->assertSame('struct any*', $type->getCType('*'));
        $this->assertSame('?\FFI\CData', $type->getPhpTypes());
        $this->assertSame('\FFI\CData|null', $type->getPhpDocTypes());
        $this->assertSame(['field1' => $field1, 'field2' => $field2], $type->getLayout());
        $this->assertFalse($type->isUnion());
    }

    public function testGetterWithDeclarationName()
    {
        $type = (new Struct('any', false))
            ->withDeclarationName('some')
            ->add('field1', $field1 = new Builtin('int'))
            ->add('field2', $field2 = new Builtin('int'));

        $this->assertSame('some', $type->getName());
        $this->assertSame('some', $type->getDeclarationName());
        $this->assertSame('any', $type->getCName());
        $this->assertSame('some', $type->getCType());
        $this->assertSame('some*', $type->getCType('*'));
        $this->assertSame('?\FFI\CData', $type->getPhpTypes());
        $this->assertSame('\FFI\CData|null', $type->getPhpDocTypes());
        $this->assertSame(['field1' => $field1, 'field2' => $field2], $type->getLayout());
        $this->assertFalse($type->isUnion());
    }

    public function testGetterWithUnionAndWithoutDeclarationName()
    {
        $type = (new Struct('any', true))
            ->add('field1', $field1 = new Builtin('int'))
            ->add('field2', $field2 = new Builtin('int'));

        $this->assertSame('any', $type->getName());
        $this->assertSame('', $type->getDeclarationName());
        $this->assertSame('any', $type->getCName());
        $this->assertSame('union any', $type->getCType());
        $this->assertSame('union any*', $type->getCType('*'));
        $this->assertSame('?\FFI\CData', $type->getPhpTypes());
        $this->assertSame('\FFI\CData|null', $type->getPhpDocTypes());
        $this->assertSame(['field1' => $field1, 'field2' => $field2], $type->getLayout());
        $this->assertTrue($type->isUnion());
    }
}
