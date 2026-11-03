<?php

declare(strict_types=1);

namespace App\Tests\Integration\Entity;

use App\Entity\User;
use App\Tests\Factory\UserFactory;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

#[CoversClass(User::class)]
final class UserTest extends KernelTestCase
{
    public function testGetUserIdentifier(): void {
        $mail = 'test@example.com';
        /** @var User $user */
        $user = UserFactory::createOne(['mail' => $mail]);
        $this->assertSame($mail, $user->getUserIdentifier());
    }

    public function testGetPassword(): void {
        /** @var User $user */
        $user = UserFactory::createOne();
        $user->password = '$2y$10$hashedpassword';
        $this->assertSame('$2y$10$hashedpassword', $user->getPassword());
    }

    public function testGetRoles(): void {
        /** @var User $user */
        $user = UserFactory::createOne();
        $this->assertSame([User::ROLE_USER], $user->getRoles());
    }

    public function testEraseCredentials(): void {
        /** @var User $user */
        $user = UserFactory::createOne();
        $user->eraseCredentials();
        $this->expectNotToPerformAssertions();
    }

    public function testAddUnknownRole(): void {
        $this->expectException(\LogicException::class);
        /** @var User $user */
        $user = UserFactory::createOne();
        $user->addRole('unknown');
    }

    public function testDeleteRole(): void {
        /** @var User $user */
        $user = UserFactory::createOne();
        $user->deleteRole(User::ROLE_USER);
        $this->assertSame([], $user->getRoles());
    }
}
