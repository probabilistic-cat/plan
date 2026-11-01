<?php

declare(strict_types=1);

namespace App\Tests\Factory;

use App\Entity\TaskStatus;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;

/** @extends PersistentObjectFactory<TaskStatus> */
final class TaskStatusFactory extends PersistentObjectFactory
{
    public static function class(): string {
        return TaskStatus::class;
    }

    protected function defaults(): array {
        return [
            'code' => self::faker()->randomElement([
                TaskStatus::TODO,
                TaskStatus::INPROGRESS,
                TaskStatus::COMPLETED,
            ]),
        ];
    }
}
