<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'task')]
#[ORM\Index(name: 'ix__task__user_id', columns: ['user_id'])]
#[ORM\Index(name: 'ix__task_task_status__task_status_code', columns: ['task_status_code'])]
class Task
{
    private const CREATED_DEFAULT = '1999-12-31 21:00:00';

    #[ORM\Id]
    #[ORM\Column(options: ['unsigned' => true])]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private int $taskId;

    #[ORM\Column(nullable: true, options: ['unsigned' => true])]
    private ?int $taskParentId = null;

    #[ORM\Column()]
    private string $taskName;

    #[ORM\Column(type: 'text', length: 65535, nullable: true)]
    private ?string $taskDescription = null;

    #[ORM\Column(options: ['default' => 0])]
    private int $taskEstimationTime = 0;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $taskDueDate = null;

    #[ORM\Column(options: ['default' => '1999-12-31 20:00:00'])]
    private \DateTime $created;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $modified = null;

    #[ORM\ManyToOne()]
    #[ORM\JoinColumn(name: 'task_status_code', referencedColumnName: 'task_status_code', nullable: false, onDelete: 'NO ACTION', options: ['default' => 'todo'])]
    private TaskStatus $taskStatus;

    #[ORM\ManyToOne()]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'user_id', nullable: false, onDelete: 'NO ACTION')]
    private User $user;

    public function __construct(User $user, TaskStatus $taskStatus)
    {
        $this->created = new \DateTime(self::CREATED_DEFAULT);
        $this->user = $user;
        $this->taskStatus = $taskStatus;
    }

    public function getTaskId(): int
    {
        return $this->taskId;
    }

    public function setTaskParentId(int $taskParentId): void
    {
        $this->taskParentId = $taskParentId;
    }

    public function getTaskParentId(): ?int
    {
        return $this->taskParentId;
    }

    public function setTaskName(string $taskName): void
    {
        $this->taskName = $taskName;
    }

    public function getTaskName(): string
    {
        return $this->taskName;
    }

    public function setTaskDescription(string $taskDescription): void
    {
        $this->taskDescription = $taskDescription;
    }

    public function getTaskDescription(): ?string
    {
        return $this->taskDescription;
    }

    public function setTaskEstimationTime(int $taskEstimationTime): void
    {
        $this->taskEstimationTime = $taskEstimationTime;
    }

    public function getTaskEstimationTime(): int
    {
        return $this->taskEstimationTime;
    }

    public function setTaskDueDate(\DateTime $taskDueDate): void
    {
        $this->taskDueDate = $taskDueDate;
    }

    public function getTaskDueDate(): ?\DateTime
    {
        return $this->taskDueDate;
    }

    public function getCreated(): \DateTime
    {
        return $this->created;
    }

    public function getModified(): ?\DateTime
    {
        return $this->modified;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setTaskStatus(TaskStatus $taskStatus): void
    {
        $this->taskStatus = $taskStatus;
    }

    public function getTaskStatus(): TaskStatus
    {
        return $this->taskStatus;
    }
}
