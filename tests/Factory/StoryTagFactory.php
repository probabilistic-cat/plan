<?php

declare(strict_types=1);

namespace App\Tests\Factory;

use App\Entity\StoryTag;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;

/** @extends PersistentObjectFactory<StoryTag> */
final class StoryTagFactory extends PersistentObjectFactory
{
    public static function class(): string {
        return StoryTag::class;
    }

    protected function defaults(): array {
        return [
            'tag' => TagFactory::new(),
            'story' => StoryFactory::new(),
        ];
    }
}
