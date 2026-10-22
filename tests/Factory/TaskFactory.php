<?php

declare(strict_types=1);

namespace App\Tests\Factory;

use App\Entity\Task;
use App\Entity\TaskStatus;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;

/** @extends PersistentObjectFactory<Task> */
final class TaskFactory extends PersistentObjectFactory
{
    public static function class(): string {
        return Task::class;
    }

    protected function defaults(): array {
        return [
            'taskName' => self::faker()->sentence(3),
            'taskDescription' => self::faker()->optional()->text(255),
            'taskEstimationTime' => 0,
            'user' => UserFactory::new(),
            'taskStatus' => TaskStatusFactory::findOrCreate(['taskStatusCode' => TaskStatus::TODO]),
        ];
    }
}
