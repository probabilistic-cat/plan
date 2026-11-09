<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Common\IdField;
use App\Entity\Common\TimestampFields;
use App\Repository\StoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StoryRepository::class)]
#[ORM\Table(name: 'story')]
#[ORM\Index(name: 'ix__story__creator_id', columns: ['creator_id'])]
#[ORM\Index(name: 'ix__story__epic_id', columns: ['epic_id'])]
#[ORM\HasLifecycleCallbacks]
final class Story
{
    use IdField;
    use TimestampFields;

    #[ORM\Column(type: Types::TEXT)]
    public string $name;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    public ?string $description = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'creator_id', referencedColumnName: 'id', nullable: false)]
    public User $creator;

    #[ORM\ManyToOne(targetEntity: Epic::class, inversedBy: 'stories')]
    #[ORM\JoinColumn(name: 'epic_id', referencedColumnName: 'id', nullable: true)]
    public ?Epic $epic = null;

    /** @var Collection<int, Task> */
    #[ORM\OneToMany(targetEntity: Task::class, mappedBy: 'story', cascade: ['persist'])]
    private(set) Collection $tasks;

    /** @var Collection<int, StoryTag> */
    #[ORM\OneToMany(targetEntity: StoryTag::class, mappedBy: 'story', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private(set) Collection $storyTags;

    public function __construct() {
        $this->tasks = new ArrayCollection();
        $this->storyTags = new ArrayCollection();
    }

    public function addStoryTag(StoryTag $storyTag): void {
        if (!$this->storyTags->contains($storyTag)) {
            $storyTag->story = $this;
            $this->storyTags->add($storyTag);
        }
    }

    public function removeStoryTag(StoryTag $storyTag): void {
        $this->storyTags->removeElement($storyTag);
    }
}
