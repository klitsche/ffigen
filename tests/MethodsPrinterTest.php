<?php

declare(strict_types=1);

namespace Klitsche\FFIGen;

use Klitsche\FFIGen\Types\Array_;
use Klitsche\FFIGen\Types\Builtin;
use Klitsche\FFIGen\Types\CharPointer;
use Klitsche\FFIGen\Types\Enum;
use Klitsche\FFIGen\Types\Function_;
use Klitsche\FFIGen\Types\FunctionPointer;
use Klitsche\FFIGen\Types\Pointer;
use Klitsche\FFIGen\Types\Struct;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Klitsche\FFIGen\MethodsPrinter
 */
class MethodsPrinterTest extends TestCase
{
    public function testPrint()
    {
        $typesCollection = new TypesCollection(
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
        );

        $collector = new MethodsCollector();
        $collection = $collector->collect(
            $typesCollection
        );

        $printer = new MethodsPrinter($collection);

        $result = $printer->print('TestNamespace');

        $this->assertSame(
            $result,
            <<<PHP
            <?php
            /**
             * This file is generated! Do not edit directly.
             */
            
            declare(strict_types=1);
            
            namespace TestNamespace;
            
            trait Methods
            {
                abstract public static function getFFI(): \FFI;
            
                /**
                 * @param \FFI\CData|null \$arg1 char*
                 * @param \FFI\CData|null \$arg2 (**)()*(char*)[][8][]
                 * @return \FFI\CData|null char**
                 */
                public static function func1(?\FFI\CData \$arg1, ?\FFI\CData \$arg2): ?\FFI\CData
                {
                    return static::getFFI()->func1(\$arg1, \$arg2);
                }
            
                /**
                 * @param string|null \$arg1 const char*
                 * @param \FFI\CData|null \$arg2 char*
                 * @return string|null const char*
                 */
                public static function func2(?string \$arg1, ?\FFI\CData \$arg2): ?string
                {
                    return static::getFFI()->func2(\$arg1, \$arg2);
                }
            
                public static function func3(): void
                {
                    static::getFFI()->func3();
                }
            }

            PHP
        );
    }
}
