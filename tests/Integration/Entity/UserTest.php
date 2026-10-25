<?php

declare(strict_types=1);

namespace App\Tests\Integration\Entity;

use App\Entity\User;
use App\Tests\Factory\UserFactory;
use App\Tests\Integration\IntegrationTestCase;

final class UserTest extends IntegrationTestCase
{
    public const string USER_NAME = 'test';

    public function testUserFields(): void {
        /** @var User $user */
        $user = UserFactory::createOne([
            'name' => self::USER_NAME,
            'active' => true,
        ]);

        $this->assertSame(self::USER_NAME, $user->name);
        $this->assertTrue($user->active);
    }
}
