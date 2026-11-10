<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Common\IdField;
use App\Entity\Common\TimestampFields;
use App\Repository\TaskRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
#[ORM\Table(name: 'task')]
#[ORM\Index(name: 'ix__task__creator_id', columns: ['creator_id'])]
#[ORM\Index(name: 'ix__task__assignee_id', columns: ['assignee_id'])]
#[ORM\Index(name: 'ix__task__task_status_id', columns: ['task_status_id'])]
#[ORM\Index(name: 'ix__task__story_id', columns: ['story_id'])]
#[ORM\HasLifecycleCallbacks]
final class Task
{
    use IdField;
    use TimestampFields;

    public const int PRIORITY_CRITICAL = 50;
    public const int PRIORITY_HIGH = 40;
    public const int PRIORITY_MEDIUM = 30;
    public const int PRIORITY_LOW = 20;
    public const int PRIORITY_LOWEST = 10;

    #[ORM\Column(type: Types::TEXT)]
    public string $name;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    public ?string $description = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    public ?int $estMinutes = null;

    #[ORM\Column(type: Types::BIGINT, nullable: true)]
    public ?int $dueDate = null;

    #[ORM\Column(type: Types::SMALLINT)]
    #[Assert\Range(min: 0, max: 50)]
    public int $priority;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'creator_id', referencedColumnName: 'id', nullable: false)]
    public User $creator;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'assignee_id', referencedColumnName: 'id', nullable: true)]
    public ?User $assignee = null;

    #[ORM\ManyToOne(targetEntity: TaskStatus::class)]
    #[ORM\JoinColumn(name: 'task_status_id', referencedColumnName: 'id', nullable: false)]
    public TaskStatus $taskStatus;

    #[ORM\ManyToOne(targetEntity: Story::class, inversedBy: 'tasks')]
    #[ORM\JoinColumn(name: 'story_id', referencedColumnName: 'id', nullable: true)]
    public ?Story $story = null;

    /** @var Collection<int, TaskTag> */
    #[ORM\OneToMany(targetEntity: TaskTag::class, mappedBy: 'task', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private(set) Collection $taskTags;

    public function __construct() {
        $this->taskTags = new ArrayCollection();
    }

    public function addTaskTag(TaskTag $taskTag): void {
        if (!$this->taskTags->contains($taskTag)) {
            $taskTag->task = $this;
            $this->taskTags->add($taskTag);
        }
    }

    public function removeTaskTag(TaskTag $taskTag): void {
        $this->taskTags->removeElement($taskTag);
    }

    /** @param Collection<int, TimeLog> $timeLogs */
    public function getTotalLoggedMinutes(Collection $timeLogs): int {
        return $timeLogs->reduce(
            static fn (int $carry, TimeLog $timeLog): int => $carry + $timeLog->minutes,
            0,
        );
    }
}
