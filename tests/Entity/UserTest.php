<?php

declare(strict_types=1);

namespace App\Tests\Entity;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserTest extends KernelTestCase
{
    public const string USER_NAME = 'test';
    public const string USER_MAIL_TAIL = '@test.notreal';
    public const string USER_PASSWORD = '123456';
    public const bool USER_ACTIVE = true;

    private EntityManagerInterface $em;
    private ?int $userId;

    protected function setUp(): void {
        parent::setUp();
        self::bootKernel();
        $container = static::getContainer();
        $this->em = $container->get(EntityManagerInterface::class);
        $this->userId = TestEntityHelper::createTestUser($this->em);
    }

    protected function tearDown(): void {
        parent::tearDown();
        TestEntityHelper::deleteTestUser($this->em, $this->userId);
    }

    public function testUserFields(): void {
        $user = TestEntityHelper::getTestUser($this->em, $this->userId);
        $this->assertEquals(self::USER_NAME, $user->getUserName());
        $this->assertEquals(self::USER_ACTIVE, $user->getUserActive());
    }
}
