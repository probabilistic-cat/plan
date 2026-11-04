<?php

declare(strict_types=1);

namespace App\Tests\Factory;

use App\Entity\Task;
use App\Entity\TaskStatus;
use Doctrine\Common\Collections\ArrayCollection;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;

/** @extends PersistentObjectFactory<Task> */
final class TaskFactory extends PersistentObjectFactory
{
    public static function class(): string {
        return Task::class;
    }

    protected function defaults(): array {
        return [
            'name' => self::faker()->sentence(3),
            'description' => self::faker()->optional()->text(),
            'estMinutes' => null,
            'priority' => Task::PRIORITY_MEDIUM,
            'creator' => UserFactory::new(),
            'assignee' => null,
            'taskStatus' => TaskStatusFactory::findOrCreate(['code' => TaskStatus::TODO]),
            'story' => StoryFactory::new(),
            'taskTags' => new ArrayCollection(),
        ];
    }
}
