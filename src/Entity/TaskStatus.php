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

    public function __construct(string $taskStatusCode)
    {
        if (!in_array($taskStatusCode, [self::TODO, self::INPROGRESS, self::COMPLETED], true)) {
            throw new \InvalidArgumentException('Wrong task status code.');
        }
        $this->taskStatusCode = $taskStatusCode;
    }

    /**
     * Get taskStatusCode
     */
    public function getTaskStatusCode(): string
    {
        return $this->taskStatusCode;
    }
}
