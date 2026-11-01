<?php

declare(strict_types=1);

namespace App\Tests\Story;

use App\Entity\User;
use App\Tests\Factory\UserFactory;
use Zenstruck\Foundry\Story;

final class UserAdminStory extends Story
{
    public const string STATE = 'admin';

    public function build(): void {
        /** @var User $admin */
        $admin = UserFactory::createOne();
        $admin->addRole(User::ROLE_ADMIN);
        $this->addState(self::STATE, $admin);
    }
}
