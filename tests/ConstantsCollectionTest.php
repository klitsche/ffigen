<?php

declare(strict_types=1);

namespace Klitsche\FFIGen;

use PHPUnit\Framework\TestCase;

/**
 * @covers \Klitsche\FFIGen\ConstantsCollection
 */
class ConstantsCollectionTest extends TestCase
{
    public function testFilter(): void
    {
        $collection = new ConstantsCollection(
            new Constant('DEF_ONE', 1),
            new Constant('DEF_TWO', 2),
            new Constant('ENUM_A_ONE', 1),
            new Constant('ENUM_A_TWO', 2),

        );
        $collection->add(new Constant('EXTRA_ONE', 1));
        $collection->add(new Constant('EXTRA_TWO', 2));

        $this->assertCount(6, $collection);

        $filteredCollection = $collection->filter(['/_TWO$/']);

        $this->assertCount(3, $filteredCollection);
        foreach ($filteredCollection as $const) {
            $this->assertSame(1, $const->getValue());
        }
    }

    public function testEmpty(): void
    {
        $collection = new ConstantsCollection();

        $this->assertCount(0, $collection);
    }
}
