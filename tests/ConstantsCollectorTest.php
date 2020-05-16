<?php

declare(strict_types=1);

namespace Klitsche\FFIGen;

use Klitsche\FFIGen\Types\Enum;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Klitsche\FFIGen\ConstantsCollector
 */
class ConstantsCollectorTest extends TestCase
{
    public function testCollect(): void
    {
        $collector = new ConstantsCollector();
        $collection = $collector->collect(
            new DefinesCollection(
                new Define('DEF_ONE', 1),
                new Define('DEF_TWO', 2)
            ),
            new TypesCollection(
                (new Enum('enum_a'))
                    ->add('ENUM_A_ONE', 1)
                    ->add('ENUM_A_TWO', 2),
                (new Enum('enum_b'))
                    ->add('ENUM_B_ONE', 1)
                    ->add('ENUM_B_TWO', 2),
            )
        );

        $constants = [];
        foreach ($collection as $name => $constant) {
            $constants[$name] = $constant->getValue();
        }
        $this->assertSame(
            [
                'DEF_ONE' => 1,
                'DEF_TWO' => 2,
                'ENUM_A_ONE' => 1,
                'ENUM_A_TWO' => 2,
                'ENUM_B_ONE' => 1,
                'ENUM_B_TWO' => 2,
            ],
            $constants
        );
    }
}
