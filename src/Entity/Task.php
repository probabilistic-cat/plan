<?php

namespace App\Entity;

class Task
{
    private const CREATED_DEFAULT = '1999-12-31 21:00:00';

    private int $taskId;
    private ?int $taskParentId = null;
    private string $taskName;
    private ?string $taskDescription = null;
    private int $taskEstimationTime = 0;
    private ?\DateTime $taskDueDate = null;
    private \DateTime $created;
    private ?\DateTime $modified = null;

    public function __construct(private User $user, private TaskStatus $taskStatus)
    {
        $this->created = new \DateTime(self::CREATED_DEFAULT);
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
