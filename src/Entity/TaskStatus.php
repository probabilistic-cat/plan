<?php

namespace App\Entity;

/**
 * TaskStatus
 */
class TaskStatus
{
    public const TODO = 'todo';
    public const INPROGRESS = 'in_progress';
    public const COMPLETED = 'completed';

    private string $taskStatusCode;

    /**
     * Get taskStatusCode
     */
    public function getTaskStatusCode(): string
    {
        return $this->taskStatusCode;
    }
}
