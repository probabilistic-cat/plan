<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Service\PasswordHandler;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[CoversClass(PasswordHandler::class)]
final class PasswordHandlerTest extends TestCase
{
    public function testGetHashedPassword(): void {
        $passwordHasherMock = $this->createMock(UserPasswordHasherInterface::class);
        $userStub = $this->createStub(PasswordAuthenticatedUserInterface::class);

        $passwordHasherMock->expects($this->once())
            ->method('hashPassword')
            ->with($userStub, 'plainPassword123')
            ->willReturn('hashed_password_string');

        $service = new PasswordHandler($passwordHasherMock);
        $hashedPassword = $service->getHashedPassword($userStub, 'plainPassword123');

        $this->assertSame('hashed_password_string', $hashedPassword);
    }
}
