<?php

namespace App\Entity;

/**
 * Task
 */
class Task
{
    /**
     * @var integer
     */
    private $taskId;

    /**
     * @var integer
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
     * @var integer
     */
    private $taskEstimationTime = '0';

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
     * @var \App\Entity\User
     */
    private $user;

    /**
     * @var \App\Entity\TaskStatus
     */
    private $taskStatusCode;


    /**
     * Get taskId
     *
     * @return integer
     */
    public function getTaskId()
    {
        return $this->taskId;
    }

    /**
     * Set taskParentId
     *
     * @param integer $taskParentId
     *
     * @return Task
     */
    public function setTaskParentId($taskParentId)
    {
        $this->taskParentId = $taskParentId;

        return $this;
    }

    /**
     * Get taskParentId
     *
     * @return integer
     */
    public function getTaskParentId()
    {
        return $this->taskParentId;
    }

    /**
     * Set taskName
     *
     * @param string $taskName
     *
     * @return Task
     */
    public function setTaskName($taskName)
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
     *
     * @return Task
     */
    public function setTaskDescription($taskDescription)
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
     * @param integer $taskEstimationTime
     *
     * @return Task
     */
    public function setTaskEstimationTime($taskEstimationTime)
    {
        $this->taskEstimationTime = $taskEstimationTime;

        return $this;
    }

    /**
     * Get taskEstimationTime
     *
     * @return integer
     */
    public function getTaskEstimationTime()
    {
        return $this->taskEstimationTime;
    }

    /**
     * Set taskDueDate
     *
     * @param \DateTime $taskDueDate
     *
     * @return Task
     */
    public function setTaskDueDate($taskDueDate)
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
     *
     * @return Task
     */
    public function setCreated($created)
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
     *
     * @return Task
     */
    public function setModified($modified)
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
     *
     * @param \App\Entity\User $user
     *
     * @return Task
     */
    public function setUser(\App\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \App\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set taskStatusCode
     *
     * @param \App\Entity\TaskStatus $taskStatusCode
     *
     * @return Task
     */
    public function setTaskStatusCode(\App\Entity\TaskStatus $taskStatusCode = null)
    {
        $this->taskStatusCode = $taskStatusCode;

        return $this;
    }

    /**
     * Get taskStatusCode
     *
     * @return \App\Entity\TaskStatus
     */
    public function getTaskStatusCode()
    {
        return $this->taskStatusCode;
    }
}
