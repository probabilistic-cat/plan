<?php

declare(strict_types=1);

namespace App\Tests\Factory;

use App\Entity\TimeLog;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;

/** @extends PersistentObjectFactory<TimeLog> */
final class TimeLogFactory extends PersistentObjectFactory
{
    public static function class(): string {
        return TimeLog::class;
    }

    protected function defaults(): array {
        return [
            'user' => UserFactory::new(),
            'task' => TaskFactory::new(),
            'minutes' => self::faker()->numberBetween(5, 480),
        ];
    }
}
