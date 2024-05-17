<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="task", indexes={@ORM\Index(name="ix__task_task_status__task_status_code", columns={"task_status_code"}), @ORM\Index(name="ix__task__user_id", columns={"user_id"})})
 * @ORM\Entity
 */
class Task
{
    private const CREATED_DEFAULT = '1999-12-31 21:00:00';

    /**
     * @ORM\Column(name="task_id", type="integer", nullable=false, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private int $taskId;
    /**
     * @ORM\Column(name="task_parent_id", type="integer", nullable=true, options={"unsigned"=true})
     */
    private ?int $taskParentId = null;
    /**
     * @ORM\Column(name="task_name", type="string", length=255, nullable=false)
     */
    private string $taskName;
    /**
     * @ORM\Column(name="task_description", type="text", length=65535, nullable=true)
     */
    private ?string $taskDescription = null;
    /**
     * @ORM\Column(name="task_estimation_time", type="integer", nullable=false)
     */
    private int $taskEstimationTime = 0;
    /**
     * @ORM\Column(name="task_due_date", type="datetime", nullable=true)
     */
    private ?\DateTime $taskDueDate = null;
    /**
     * @ORM\Column(name="created", type="datetime", nullable=false, options={"default"="1999-12-31 20:00:00"})
     */
    private \DateTime $created;
    /**
     * @ORM\Column(name="modified", type="datetime", nullable=true)
     */
    private ?\DateTime $modified = null;
    /**
     * @ORM\ManyToOne(targetEntity="TaskStatus")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="task_status_code", referencedColumnName="task_status_code")
     * })
     */
    private TaskStatus $taskStatus;
    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="user_id")
     * })
     */
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
