<?php

namespace App\Entity;

/**
 * TaskStatus
 */
class TaskStatus
{
    const TODO = 'todo';
    const INPROGRESS = 'in_progress';
    const COMPLETED = 'completed';

    /**
     * @var string
     */
    private $taskStatusCode;


    /**
     * Get taskStatusCode
     *
     * @return string
     */
    public function getTaskStatusCode()
    {
        return $this->taskStatusCode;
    }
}
