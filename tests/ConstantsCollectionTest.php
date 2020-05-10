<?php

declare(strict_types=1);

namespace Klitsche\FFIGen;

use Klitsche\FFIGen\Types\Enum;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Klitsche\FFIGen\ConstantsCollection
 */
class ConstantsCollectionTest extends TestCase
{
    public function testFilter(): void
    {
        $collection = new ConstantsCollection(
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
            ),
            ['/_TWO$/']
        );
        $collection->add(new Constant('EXTRA_ONE', 1));
        $collection->add(new Constant('EXTRA_TWO', 2));

        $this->assertCount(4, $collection);
        foreach ($collection as $const) {
            $this->assertSame(1, $const->getValue());
        }
    }

    public function testEmpty(): void
    {
        $collection = new ConstantsCollection(new DefinesCollection(), new TypesCollection());

        $this->assertCount(0, $collection);
    }
}
