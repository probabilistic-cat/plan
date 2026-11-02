<?php

declare(strict_types=1);

namespace App\Tests\Factory;

use App\Entity\TaskTag;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;

/** @extends PersistentObjectFactory<TaskTag> */
final class TaskTagFactory extends PersistentObjectFactory
{
    public static function class(): string {
        return TaskTag::class;
    }

    protected function defaults(): array {
        return [
            'tag' => TagFactory::new(),
            'task' => TaskFactory::new(),
        ];
    }
}
