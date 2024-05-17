<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity]
#[ORM\Table(name: 'user')]
#[ORM\UniqueConstraint(name: 'iu__user__user_mail', columns: ['user_mail'])]
class User implements UserInterface
{
    private const CREATED_DEFAULT = '1999-12-31 21:00:00';

    #[ORM\Id]
    #[ORM\Column(name: 'user_id', type: 'integer', nullable: false, options: ['unsigned' => true])]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private int $userId;

    #[ORM\Column(name: 'user_name', type: 'string', length: 100, nullable: false)]
    private string $userName;

    #[ORM\Column(name: 'user_password', type: 'string', length: 60, nullable: false, options: ['fixed' => true])]
    private string $userPassword;

    private string $userPlainPassword;

    #[ORM\Column(name: 'user_mail', type: 'string', length: 100, nullable: false)]
    private string $userMail;

    #[ORM\Column(name: 'user_active', type: 'boolean', nullable: false, options: ['default' => 0])]
    private bool $userActive = false;

    #[ORM\Column(name: 'created', type: 'datetime', nullable: false, options: ['default' => '1999-12-31 21:00:00'])]
    private \DateTime $created;

    #[ORM\Column(name: 'modified', type: 'datetime', nullable: true, options: ['default' => 'CURRENT_TIMESTAMP'])]
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

    public function getSalt(): ?string
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
