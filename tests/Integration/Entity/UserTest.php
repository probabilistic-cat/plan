<?php

declare(strict_types=1);

namespace App\Tests\Integration\Entity;

use App\Tests\Factory\UserFactory;
use App\Tests\Integration\IntegrationTestCase;

final class UserTest extends IntegrationTestCase
{
    public const string USER_NAME = 'test';

    public function testUserFields(): void {
        $user = UserFactory::createOne([
            'userName' => self::USER_NAME,
            'userActive' => true,
        ]);

        $this->assertSame(self::USER_NAME, $user->getUserName());
        $this->assertTrue($user->getUserActive());
    }
}
