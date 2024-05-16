<?php

namespace App\Tests\Entity;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\ByteString;

class TestEntityHelper
{
    private static function getUniqueUserMail(): string
    {
        return ByteString::fromRandom()->toString().UserTest::USER_MAIL_TAIL;
    }

    public static function createTestUser(EntityManagerInterface $em): int
    {
        $user = new User();
        $user->setUserName(UserTest::USER_NAME);
        $user->setUserMail(self::getUniqueUserMail());
        $user->setUserPassword(UserTest::USER_PASSWORD);
        $user->setUserActive(UserTest::USER_ACTIVE);
        $em->persist($user);
        $em->flush();

        return $user->getUserId();
    }

    public static function getTestUser(EntityManagerInterface $em, int $userId): User
    {
        return $em->getRepository(User::class)->findOneBy(['userId' => $userId]);
    }

    public static function deleteTestUser(EntityManagerInterface $em, int $userId): void
    {
        $user = self::getTestUser($em, $userId);
        $em->remove($user);
        $em->flush();
    }
}
