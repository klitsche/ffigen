<?php

declare(strict_types=1);

namespace Klitsche\FFIGen\Adapter\PHPCParser;

use Klitsche\FFIGen\Types\Array_;
use Klitsche\FFIGen\Types\Builtin;
use Klitsche\FFIGen\Types\CharPointer;
use Klitsche\FFIGen\Types\Enum;
use Klitsche\FFIGen\Types\Function_;
use Klitsche\FFIGen\Types\FunctionPointer;
use Klitsche\FFIGen\Types\Pointer;
use Klitsche\FFIGen\Types\Struct;
use Klitsche\FFIGen\TypesCollection;
use PHPCParser\CParser;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Klitsche\FFIGen\Adapter\PHPCParser\TypesCollector
 * @covers \Klitsche\FFIGen\TypesCollection
 * @covers \Klitsche\FFIGen\Types\Array_
 * @covers \Klitsche\FFIGen\Types\Builtin
 * @covers \Klitsche\FFIGen\Types\CharPointer
 * @covers \Klitsche\FFIGen\Types\Enum
 * @covers \Klitsche\FFIGen\Types\Function_
 * @covers \Klitsche\FFIGen\Types\FunctionPointer
 * @covers \Klitsche\FFIGen\Types\Pointer
 * @covers \Klitsche\FFIGen\Types\Struct
 * @covers \Klitsche\FFIGen\Types\Type
 * @covers \Klitsche\FFIGen\Adapter\PHPCParser\Compiler\Compiler
 * @covers \Klitsche\FFIGen\Adapter\PHPCParser\Compiler\ArrayTypeTranslator
 * @covers \Klitsche\FFIGen\Adapter\PHPCParser\Compiler\AttributedTypeTranslator
 * @covers \Klitsche\FFIGen\Adapter\PHPCParser\Compiler\BuiltinTypeTranslator
 * @covers \Klitsche\FFIGen\Adapter\PHPCParser\Compiler\EnumDeclTranslator
 * @covers \Klitsche\FFIGen\Adapter\PHPCParser\Compiler\FunctionDeclTranslator
 * @covers \Klitsche\FFIGen\Adapter\PHPCParser\Compiler\FunctionTypeTranslator
 * @covers \Klitsche\FFIGen\Adapter\PHPCParser\Compiler\ParenTypeTranslator
 * @covers \Klitsche\FFIGen\Adapter\PHPCParser\Compiler\PointerTypeTranslator
 * @covers \Klitsche\FFIGen\Adapter\PHPCParser\Compiler\RecordDeclTranslator
 * @covers \Klitsche\FFIGen\Adapter\PHPCParser\Compiler\TagTypeTranslator
 * @covers \Klitsche\FFIGen\Adapter\PHPCParser\Compiler\TypeDefDeclTranslator
 * @covers \Klitsche\FFIGen\Adapter\PHPCParser\Compiler\TypeDefTypeTranslator
 */
class TypesCollectorTest extends TestCase
{
    public function testCollect(): void
    {
        $parser = new CParser();
        $ast = $parser->parse(dirname(__DIR__, 2) . '/test.h');

        $collector = new TypesCollector();
        $collection = $collector->collect($ast->declarations);

        $this->assertSame(13, iterator_count($collection));
        $this->assertEquals(
            new TypesCollection(
                (new Builtin('int'))->withDeclarationName('int_t'),
                (new Builtin('float'))->withDeclarationName('float_t'),
                $charT = (new CharPointer(new Builtin('char')))->withDeclarationName('char_t'),
                (new Pointer(new Builtin('void')))->withDeclarationName('foo'),
                $structA = (new Struct('a', false))->withDeclarationName('a_t'),
                (new Struct('b', false))->withDeclarationName('b_t')
                    ->add('field1', new Pointer($structA))
                    ->add('field2', new Pointer($structA)),
                (new Enum('qux'))->withDeclarationName('grault')
                    ->add('QUUX', 0)
                    ->add('CORGE', 1),
                (new Array_(new Builtin('unsigned char'), 23))->withDeclarationName('bar'),
                (new Array_(new Array_(new Builtin('long'))))->withDeclarationName('baz'),
                // hairy is array of array of 8 pointer to pointer to function returning pointer to array of pointer to char
                $hairy = (new Array_(
                    new Array_(
                        new Pointer(
                            new FunctionPointer(
                                new Function_(
                                    new Pointer(new Array_(new CharPointer(new Builtin('char')))),
                                    [],
                                    false
                                )
                            )
                        ),
                        8
                    )
                ))->withDeclarationName('hairy'),
                (new Function_(
                    new Pointer(new CharPointer(new Builtin('char'))),
                    [
                        'arg1' => $charT,
                        'arg2' => $hairy,
                    ],
                    false
                ))->withDeclarationName('func1'),
                (new Function_(
                    (new CharPointer(new Builtin('char')))->withConst(true),
                    [
                        'arg1' => (new CharPointer(new Builtin('char')))->withConst(true),
                        'arg2' => new CharPointer(new Builtin('char')),
                    ],
                    false
                ))->withDeclarationName('func2'),
                (new Function_(
                    new Builtin('void'),
                    [
                        'arg0' => new Builtin('void'),
                    ],
                    false
                ))->withDeclarationName('func3')
            ),
            $collection
        );
    }
}
