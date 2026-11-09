<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Common\IdField;
use App\Entity\Common\TimestampFields;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'tag')]
#[ORM\UniqueConstraint(name: 'iu__tag__name', columns: ['name'])]
#[ORM\HasLifecycleCallbacks]
final class Tag
{
    use IdField;
    use TimestampFields;

    #[ORM\Column(type: Types::TEXT, length: 50, unique: true)]
    public string $name;

    /** @var Collection<int, EpicTag> */
    #[ORM\OneToMany(targetEntity: EpicTag::class, mappedBy: 'tag', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private(set) Collection $epicTags;

    /** @var Collection<int, StoryTag> */
    #[ORM\OneToMany(targetEntity: StoryTag::class, mappedBy: 'tag', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private(set) Collection $storyTags;

    /** @var Collection<int, TaskTag> */
    #[ORM\OneToMany(targetEntity: TaskTag::class, mappedBy: 'tag', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private(set) Collection $taskTags;

    public int $taskCount = 0;
    public int $storyCount = 0;
    public int $epicCount = 0;

    public function __construct() {
        $this->epicTags = new ArrayCollection();
        $this->storyTags = new ArrayCollection();
        $this->taskTags = new ArrayCollection();
    }
}
