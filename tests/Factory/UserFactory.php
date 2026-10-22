<?php

declare(strict_types=1);

namespace App\Tests\Factory;

use App\Entity\User;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;

/** @extends PersistentObjectFactory<User> */
final class UserFactory extends PersistentObjectFactory
{
    public static function class(): string {
        return User::class;
    }

    protected function defaults(): array {
        return [
            'userName' => self::faker()->userName(),
            'userMail' => self::faker()->unique()->safeEmail(),
            'userPassword' => 'password',
            'userActive' => true,
        ];
    }
}
