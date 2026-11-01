<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\User;
use App\Tests\Factory\UserFactory;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(User::class)]
final class UserTest extends TestCase
{
    public function testGetUserIdentifier(): void {
        $mail = 'test@example.com';
        $user = UserFactory::createOne(['mail' => $mail]);
        $this->assertSame($mail, $user->getUserIdentifier());
    }

    public function testGetPassword(): void {
        $user = UserFactory::createOne();
        $user->password = '$2y$10$hashedpassword';
        $this->assertSame('$2y$10$hashedpassword', $user->getPassword());
    }

    public function testGetRoles(): void {
        $user = UserFactory::createOne();
        $this->assertSame([User::ROLE_USER], $user->getRoles());
    }

    public function testEraseCredentials(): void {
        $user = UserFactory::createOne();
        $user->eraseCredentials();
        $this->expectNotToPerformAssertions();
    }

    public function testAddUnknownRole(): void {
        $this->expectException(\LogicException::class);
        $user = UserFactory::createOne();
        $user->addRole('unknown');
    }

    public function testDeleteRole(): void {
        $user = UserFactory::createOne();
        $user->deleteRole(User::ROLE_USER);
        $this->assertSame([], $user->getRoles());
    }
}
