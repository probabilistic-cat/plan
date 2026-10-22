<?php

declare(strict_types=1);

namespace App\Tests\Integration\Entity;

use App\Entity\TaskStatus;
use App\Tests\Factory\TaskFactory;
use App\Tests\Integration\IntegrationTestCase;

final class TaskTest extends IntegrationTestCase
{
    private const string TASK_NAME = 'test task';
    private const string TASK_DESCRIPTION = 'task description';

    public function testTaskFields(): void {
        $task = TaskFactory::createOne([
            'taskName' => self::TASK_NAME,
            'taskDescription' => self::TASK_DESCRIPTION,
        ]);

        $this->assertSame(self::TASK_NAME, $task->getTaskName());
        $this->assertSame(self::TASK_DESCRIPTION, $task->getTaskDescription());
        $this->assertNull($task->getTaskParentId());
        $this->assertSame(TaskStatus::TODO, $task->getTaskStatus()->getTaskStatusCode());
        $this->assertNull($task->getTaskDueDate());
    }
}
