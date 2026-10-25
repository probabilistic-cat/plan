<?php

declare(strict_types=1);

namespace App\Tests\Integration\Entity;

use App\Entity\Task;
use App\Entity\TaskStatus;
use App\Tests\Factory\TaskFactory;
use App\Tests\Integration\IntegrationTestCase;

final class TaskTest extends IntegrationTestCase
{
    private const string NAME = 'test task';
    private const string DESCRIPTION = 'task description';

    public function testTaskFields(): void {
        /** @var Task $task */
        $task = TaskFactory::createOne([
            'name' => self::NAME,
            'description' => self::DESCRIPTION,
        ]);

        $this->assertSame(self::NAME, $task->name);
        $this->assertSame(self::DESCRIPTION, $task->description);
        $this->assertSame(TaskStatus::TODO, $task->taskStatus->code);
        $this->assertNull($task->dueDate);
    }
}
