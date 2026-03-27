<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'task_status')]
class TaskStatus
{
    public const TODO = 'todo';
    public const INPROGRESS = 'in_progress';
    public const COMPLETED = 'completed';

    #[ORM\Id]
    #[ORM\Column(length: 32)]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private readonly string $taskStatusCode;

    public function __construct(string $taskStatusCode) {
        if (!in_array($taskStatusCode, [self::TODO, self::INPROGRESS, self::COMPLETED], true)) {
            throw new \InvalidArgumentException('Wrong task status code.');
        }
        $this->taskStatusCode = $taskStatusCode;
    }

    public function getTaskStatusCode(): string {
        return $this->taskStatusCode;
    }
}
