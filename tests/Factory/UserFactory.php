<?php

declare(strict_types=1);

namespace App\Tests\Factory;

use App\Entity\User;
use App\Service\PasswordHandler;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;

/** @extends PersistentObjectFactory<User> */
final class UserFactory extends PersistentObjectFactory
{
    public function __construct(
        private readonly ?PasswordHandler $passwordHandler = null,
    ) {
        parent::__construct();
    }

    public static function class(): string {
        return User::class;
    }

    protected function defaults(): array {
        return [
            'mail' => self::faker()->unique()->safeEmail(),
            'name' => self::faker()->unique()->userName(),
            'password' => 'Pass1234567890',
            'active' => true,
        ];
    }

    protected function initialize(): self {
        return $this->afterInstantiate(function (User $user): void {
            if ($this->passwordHandler instanceof PasswordHandler) {
                $user->password = $this->passwordHandler->getHashedPassword($user, $user->password);
            }
            $user->addRole(User::ROLE_USER);
        });
    }
}
