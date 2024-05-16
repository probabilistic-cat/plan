<?php

namespace App\Entity;

/**
 * Task
 */
class Task
{
    /**
     * @var int
     */
    private $taskId;

    /**
     * @var int
     */
    private $taskParentId;

    /**
     * @var string
     */
    private $taskName;

    /**
     * @var string
     */
    private $taskDescription;

    /**
     * @var int
     */
    private $taskEstimationTime = 0;

    /**
     * @var \DateTime
     */
    private $taskDueDate;

    /**
     * @var \DateTime
     */
    private $created = '1999-12-31 21:00:00';

    /**
     * @var \DateTime
     */
    private $modified;

    /**
     * @var User
     */
    private $user;

    /**
     * @var TaskStatus
     */
    private $taskStatus;

    public function __construct()
    {
        $this->created = new \DateTime($this->created);
    }

    /**
     * Get taskId
     *
     * @return int
     */
    public function getTaskId()
    {
        return $this->taskId;
    }

    /**
     * Set taskParentId
     *
     * @param int $taskParentId
     */
    public function setTaskParentId($taskParentId): self
    {
        $this->taskParentId = $taskParentId;

        return $this;
    }

    /**
     * Get taskParentId
     *
     * @return int
     */
    public function getTaskParentId()
    {
        return $this->taskParentId;
    }

    /**
     * Set taskName
     *
     * @param string $taskName
     */
    public function setTaskName($taskName): self
    {
        $this->taskName = $taskName;

        return $this;
    }

    /**
     * Get taskName
     *
     * @return string
     */
    public function getTaskName()
    {
        return $this->taskName;
    }

    /**
     * Set taskDescription
     *
     * @param string $taskDescription
     */
    public function setTaskDescription($taskDescription): self
    {
        $this->taskDescription = $taskDescription;

        return $this;
    }

    /**
     * Get taskDescription
     *
     * @return string
     */
    public function getTaskDescription()
    {
        return $this->taskDescription;
    }

    /**
     * Set taskEstimationTime
     *
     * @param int $taskEstimationTime
     */
    public function setTaskEstimationTime($taskEstimationTime): self
    {
        $this->taskEstimationTime = $taskEstimationTime;

        return $this;
    }

    /**
     * Get taskEstimationTime
     *
     * @return int
     */
    public function getTaskEstimationTime()
    {
        return $this->taskEstimationTime;
    }

    /**
     * Set taskDueDate
     *
     * @param \DateTime $taskDueDate
     */
    public function setTaskDueDate($taskDueDate): self
    {
        $this->taskDueDate = $taskDueDate;

        return $this;
    }

    /**
     * Get taskDueDate
     *
     * @return \DateTime
     */
    public function getTaskDueDate()
    {
        return $this->taskDueDate;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     */
    public function setCreated($created): self
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set modified
     *
     * @param \DateTime $modified
     */
    public function setModified($modified): self
    {
        $this->modified = $modified;

        return $this;
    }

    /**
     * Get modified
     *
     * @return \DateTime
     */
    public function getModified()
    {
        return $this->modified;
    }

    /**
     * Set user
     */
    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set taskStatus
     */
    public function setTaskStatus(TaskStatus $taskStatus): self
    {
        $this->taskStatus = $taskStatus;

        return $this;
    }

    /**
     * Get taskStatus
     *
     * @return TaskStatus
     */
    public function getTaskStatus()
    {
        return $this->taskStatus;
    }
}
