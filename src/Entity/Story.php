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
#[ORM\Table(name: 'story')]
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

    #[ORM\ManyToOne(targetEntity: Epic::class)]
    #[ORM\JoinColumn(name: 'epic_id', referencedColumnName: 'id', nullable: true, onDelete: 'NO ACTION')]
    public ?Epic $epic = null;

    /** @var Collection<int, Task> */
    #[ORM\OneToMany(targetEntity: Task::class, mappedBy: 'story', orphanRemoval: true)]
    private(set) Collection $tasks;

    public function __construct() {
        $this->tasks = new ArrayCollection();
    }
}
