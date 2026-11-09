<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'story_tag')]
#[ORM\Index(name: 'ix__story_tag__tag_id', columns: ['tag_id'])]
final class StoryTag
{
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Story::class, inversedBy: 'storyTags')]
    #[ORM\JoinColumn(name: 'story_id', referencedColumnName: 'id')]
    public Story $story;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Tag::class, inversedBy: 'storyTags')]
    #[ORM\JoinColumn(name: 'tag_id', referencedColumnName: 'id')]
    public Tag $tag;
}
