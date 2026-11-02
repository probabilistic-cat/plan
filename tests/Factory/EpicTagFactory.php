<?php

declare(strict_types=1);

namespace App\Tests\Factory;

use App\Entity\EpicTag;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;

/** @extends PersistentObjectFactory<EpicTag> */
final class EpicTagFactory extends PersistentObjectFactory
{
    public static function class(): string {
        return EpicTag::class;
    }

    protected function defaults(): array {
        return [
            'tag' => TagFactory::new(),
            'epic' => EpicFactory::new(),
        ];
    }
}
