<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Common\IdField;
use App\Entity\Common\TimestampFields;
use App\Repository\EpicRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EpicRepository::class)]
#[ORM\Table(name: 'epic')]
#[ORM\Index(name: 'ix__epic__creator_id', columns: ['creator_id'])]
#[ORM\HasLifecycleCallbacks]
final class Epic
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

    /** @var Collection<int, Story> */
    #[ORM\OneToMany(targetEntity: Story::class, mappedBy: 'epic', cascade: ['persist'])]
    private(set) Collection $stories;

    /** @var Collection<int, EpicTag> */
    #[ORM\OneToMany(targetEntity: EpicTag::class, mappedBy: 'epic', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private(set) Collection $epicTags;

    public function __construct() {
        $this->stories = new ArrayCollection();
        $this->epicTags = new ArrayCollection();
    }

    public function addEpicTag(EpicTag $epicTag): void {
        if (!$this->epicTags->contains($epicTag)) {
            $epicTag->epic = $this;
            $this->epicTags->add($epicTag);
        }
    }

    public function removeEpicTag(EpicTag $epicTag): void {
        $this->epicTags->removeElement($epicTag);
    }

    public function getTotalLoggedMinutes(): int {
        $total = 0;
        foreach ($this->stories as $story) {
            foreach ($story->tasks as $task) {
                $total += $task->getTotalLoggedMinutes();
            }
        }

        return $total;
    }
}
