<?php

declare(strict_types=1);

namespace App\Tests\Factory;

use App\Entity\Epic;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;

/** @extends PersistentObjectFactory<Epic> */
final class EpicFactory extends PersistentObjectFactory
{
    public static function class(): string {
        return Epic::class;
    }

    protected function defaults(): array {
        return [
            'name' => self::faker()->sentence(3),
            'description' => self::faker()->optional()->text(),
        ];
    }
}
