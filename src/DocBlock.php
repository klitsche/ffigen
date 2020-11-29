<?php

declare(strict_types=1);

namespace Klitsche\FFIGen;

class DocBlock
{
    public string $template = <<<PHPDOC
        /**%s
         */
        PHPDOC;

    private ?string $description;

    /**
     * @var DocBlockTag[]
     */
    private array $tags;

    public function __construct()
    {
        $this->description = null;
        $this->tags = [];
    }

    public function isEmpty(): bool
    {
        return empty($this->description) && empty($this->tags);
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return DocBlockTag[]
     */
    public function getTags(): array
    {
        return array_values($this->tags);
    }

    /**
     * @param DocBlockTag[] $tags
     */
    public function setTags(array $tags): void
    {
        $this->tags = $tags;
    }

    public function addTag(DocBlockTag $tag): void
    {
        $this->tags[] = $tag;
    }

    public function removeTag(DocBlockTag $tag): void
    {
        $this->tags = array_filter(
            $this->tags,
            function ($candidate) use ($tag) {
                return $candidate->getName() !== $tag->getName()
                    || $candidate->getValue() !== $tag->getValue();
            }
        );
    }

    public function removeTagsByName(string $name): void
    {
        $this->tags = array_filter(
            $this->tags,
            function ($candidate) use ($name) {
                return $candidate->getName() !== $name;
            }
        );
    }

    public function print(): string
    {
        $lines = [];
        if ($this->description !== null) {
            $lines[] = sprintf(' * %s', $this->description);
        }
        foreach ($this->tags as $tag) {
            $lines[] = sprintf(' * %s', $tag->print());
        }

        return sprintf($this->template, empty($lines) ? '' : "\n" . implode("\n", $lines));
    }
}
