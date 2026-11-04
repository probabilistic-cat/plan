<?php

declare(strict_types=1);

namespace App\Form\DataTransformer;

use App\Entity\EpicTag;
use App\Entity\StoryTag;
use App\Entity\Tag;
use App\Entity\TaskTag;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\DataTransformerInterface;

/** @implements DataTransformerInterface<ArrayCollection<int, EpicTag|StoryTag|TaskTag>, array<Tag>> */
abstract class EntityTagTransformer implements DataTransformerInterface
{
    /** @var array<string, EpicTag|StoryTag|TaskTag> */
    private array $existingEntityTags = [];

    /**
     * @param ArrayCollection<int, EpicTag|StoryTag|TaskTag>|null $value
     * @return array<Tag>
     */
    public function transform(mixed $value): array {
        $this->existingEntityTags = [];
        if ($value === null) {
            return [];
        }

        $tags = [];
        foreach ($value as $entityTag) {
            $this->existingEntityTags[(string)$entityTag->tag->id] = $entityTag;
            $tags[] = $entityTag->tag;
        }

        return $tags;
    }

    /**
     * @param array<Tag>|null $value
     * @return ArrayCollection<int, EpicTag|StoryTag|TaskTag>
     */
    public function reverseTransform(mixed $value): ArrayCollection {
        $result = new ArrayCollection();
        if ($value === null) {
            return $result;
        }

        foreach ($value as $tag) {
            if (array_key_exists((string)$tag->id, $this->existingEntityTags)) {
                $entityTag = $this->existingEntityTags[(string)$tag->id];
            } else {
                $entityTag = $this->createEntityTag();
                $entityTag->tag = $tag;
            }

            $result->add($entityTag);
        }

        return $result;
    }

    abstract protected function createEntityTag(): EpicTag|StoryTag|TaskTag;
}
