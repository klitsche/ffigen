<?php

declare(strict_types=1);

namespace Klitsche\FFIGen;

use PHPUnit\Framework\TestCase;

class DocBlockTest extends TestCase
{
    public function testIsEmpty(): void
    {
        $block = new DocBlock();

        $this->assertTrue($block->isEmpty());
    }

    public function testAddAndRemoveTag(): void
    {
        $tag = new DocBlockTag('any', 'value');
        $tagSame = new DocBlockTag('any', 'value');
        $tagOtherValue = new DocBlockTag('any', 'other');
        $tagOtherName = new DocBlockTag('other', 'value');

        $block = new DocBlock();
        $block->addTag($tag);
        $this->assertSame([$tag], $block->getTags());

        $block->removeTag($tagOtherValue);
        $this->assertSame([$tag], $block->getTags());

        $block->removeTag($tagOtherName);
        $this->assertSame([$tag], $block->getTags());

        $block->removeTag($tagSame);
        $this->assertSame([], $block->getTags());
    }

    public function testGetAndSetDescription(): void
    {
        $block = new DocBlock();

        $this->assertNull($block->getDescription());

        $block->setDescription('any');
        $this->assertSame('any', $block->getDescription());

        $block->setDescription(null);
        $this->assertNull($block->getDescription());
    }

    public function testPrint(): void
    {
        $block = new DocBlock();

        $this->assertSame(
            <<<TEST
            /**
             */
            TEST,
            $block->print()
        );

        $block->addTag(new DocBlockTag('any', "text\non newline"));
        $this->assertSame(
            <<<TEST
            /**
             * @any text
             * on newline
             */
            TEST,
            $block->print()
        );

        $block->setDescription("desc\non newline");
        $this->assertSame(
            <<<TEST
            /**
             * desc
             * on newline
             * @any text
             * on newline
             */
            TEST,
            $block->print()
        );
    }

    public function testGetAndSetTags(): void
    {
        $tagOne = new DocBlockTag('any', 'value');
        $tagTwo = new DocBlockTag('any', 'value');

        $block = new DocBlock();
        $this->assertSame([], $block->getTags());

        $block->setTags([$tagOne, $tagTwo]);
        $this->assertSame([$tagOne, $tagTwo], $block->getTags());
    }

    public function testRemoveTagsByName(): void
    {
        $tagOne = new DocBlockTag('any', 'value');
        $tagTwo = new DocBlockTag('any', 'value');
        $tagThree = new DocBlockTag('other', 'value');

        $block = new DocBlock();
        $block->setTags([$tagOne, $tagTwo, $tagThree]);

        $block->removeTagsByName('any');
        $this->assertSame([$tagThree], $block->getTags());
    }
}
