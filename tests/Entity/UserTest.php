<?php

namespace App\Tests\Entity;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserTest extends KernelTestCase
{
    public const USER_NAME = 'test';
    public const USER_MAIL_TAIL = '@test.notreal';
    public const USER_PASSWORD = '123456';
    public const USER_ACTIVE = true;

    private EntityManagerInterface $em;
    private ?int $userId;

    protected function setUp(): void
    {
        parent::setUp();
        $this->em = self::bootKernel()->getContainer()->get('doctrine.orm.entity_manager');
        $this->userId = TestEntityHelper::createTestUser($this->em);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        TestEntityHelper::deleteTestUser($this->em, $this->userId);
    }

    public function testUserFields(): void
    {
        $user = TestEntityHelper::getTestUser($this->em, $this->userId);
        $this->assertEquals(self::USER_NAME, $user->getUserName());
        $this->assertEquals(self::USER_ACTIVE, $user->getUserActive());
    }
}
