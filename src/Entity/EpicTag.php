<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'epic_tag')]
#[ORM\Index(name: 'ix__epic_tag__tag_id', columns: ['tag_id'])]
final class EpicTag
{
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Epic::class, inversedBy: 'epicTags')]
    #[ORM\JoinColumn(name: 'epic_id', referencedColumnName: 'id')]
    public Epic $epic;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Tag::class, inversedBy: 'epicTags')]
    #[ORM\JoinColumn(name: 'tag_id', referencedColumnName: 'id')]
    public Tag $tag;
}
