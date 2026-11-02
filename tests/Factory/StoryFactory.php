<?php

declare(strict_types=1);

namespace App\Tests\Factory;

use App\Entity\Story;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;

/** @extends PersistentObjectFactory<Story> */
final class StoryFactory extends PersistentObjectFactory
{
    public static function class(): string {
        return Story::class;
    }

    protected function defaults(): array {
        return [
            'name' => self::faker()->sentence(3),
            'description' => self::faker()->optional()->text(),
            'epic' => EpicFactory::new(),
        ];
    }
}
