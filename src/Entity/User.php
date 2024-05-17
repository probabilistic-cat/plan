<?php

namespace App\Entity;

use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface
{
    private const CREATED_DEFAULT = '1999-12-31 21:00:00';

    private int $userId;
    private string $userName;
    private string $userPassword;
    private string $userPlainPassword;
    private string $userMail;
    private bool $userActive = false;
    private \DateTime $created;
    private ?\DateTime $modified = null;

    public function __construct()
    {
        $this->created = new \DateTime(self::CREATED_DEFAULT);
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserName(string $userName): void
    {
        $this->userName = $userName;
    }

    public function getUserName(): string
    {
        return $this->userName;
    }

    public function setUserPassword(string $userPassword): void
    {
        $this->userPassword = $userPassword;
    }

    public function getUserPassword(): string
    {
        return $this->userPassword;
    }

    public function setUserPlainPassword(string $userPlainPassword): void
    {
        $this->userPlainPassword = $userPlainPassword;
    }

    public function getUserPlainPassword(): string
    {
        return $this->userPlainPassword;
    }

    public function setUserMail(string $userMail): void
    {
        $this->userMail = $userMail;
    }

    public function getUserMail(): string
    {
        return $this->userMail;
    }

    public function setUserActive(bool $userActive): void
    {
        $this->userActive = $userActive;
    }

    public function getUserActive(): bool
    {
        return $this->userActive;
    }

    public function getCreated(): \DateTime
    {
        return $this->created;
    }

    public function getModified(): ?\DateTime
    {
        return $this->modified;
    }

//    public function serialize(): string
//    {
//        return serialize([
//            $this->userId,
//            $this->userName,
//            $this->userPassword,
//        ]);
//    }

//    public function unserialize(string $serialized): void
//    {
//        [$this->userId, $this->userName, $this->userPassword] = unserialize($serialized);
//    }

    public function getPassword(): string
    {
        return $this->getUserPassword();
    }

    public function getSalt()
    {
        return null;
    }

    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    public function eraseCredentials(): void
    {
    }
}
