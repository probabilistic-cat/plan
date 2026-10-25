<?php

declare(strict_types=1);

namespace App\Tests\Factory;

use App\Entity\TaskStatus;
use Zenstruck\Foundry\Object\Instantiator;
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

    protected function initialize(): self {
        return $this->instantiateWith(
            Instantiator::withConstructor()->allowExtra(),
        );
    }

    public static function todo(): self {
        return self::new(['code' => TaskStatus::TODO]);
    }

    public static function inProgress(): self {
        return self::new(['code' => TaskStatus::INPROGRESS]);
    }

    public static function completed(): self {
        return self::new(['code' => TaskStatus::COMPLETED]);
    }
}
