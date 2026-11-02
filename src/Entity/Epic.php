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
#[ORM\Table(name: 'epic')]
#[ORM\HasLifecycleCallbacks]
final class Epic
{
    use IdField;
    use TimestampFields;

    #[ORM\Column(type: Types::TEXT)]
    public string $name;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    public ?string $description = null;

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
}
