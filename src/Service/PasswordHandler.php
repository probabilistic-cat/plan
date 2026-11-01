<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

readonly class PasswordHandler
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
    ) {}

    public function getHashedPassword(
        PasswordAuthenticatedUserInterface $user,
        #[\SensitiveParameter] string $plainPassword,
    ): string {
        return $this->passwordHasher->hashPassword($user, $plainPassword);
    }
}
