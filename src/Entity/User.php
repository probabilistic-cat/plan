<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Common\IdField;
use App\Entity\Common\TimestampFields;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ORM\Table(name: '`user`')]
#[ORM\UniqueConstraint(name: 'iu__user__mail', columns: ['mail'])]
#[ORM\UniqueConstraint(name: 'iu__user__name', columns: ['name'])]
#[ORM\HasLifecycleCallbacks]
#[UniqueEntity(fields: ['mail'], message: 'This email is already taken.', errorPath: 'mail')]
#[UniqueEntity(fields: ['name'], message: 'This name is already taken.', errorPath: 'name')]
final class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use IdField;
    use TimestampFields;

    public const string ROLE_USER = 'ROLE_USER';
    public const string ROLE_ADMIN = 'ROLE_ADMIN';

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank]
    #[Assert\Email]
    public string $mail;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 32)]
    public string $name;

    #[ORM\Column(length: 60, options: ['fixed' => true])]
    public string $password;

    #[Assert\NotBlank]
    #[Assert\PasswordStrength]
    public string $plainPassword;

    #[ORM\Column(options: ['default' => false])]
    public bool $active = false;

    /** @var list<string> */
    #[ORM\Column(type: Types::JSONB)]
    private(set) array $roles = [];

    public function getUserIdentifier(): string {
        $userMail = $this->mail;
        assert($userMail !== '');
        return $userMail;
    }

    public function getPassword(): string {
        return $this->password;
    }

    public function getRoles(): array {
        return $this->roles;
    }

    #[\Deprecated]
    public function eraseCredentials(): void {}

    public function addRole(string $role): void {
        if (!in_array($role, $this->getAllRoles(), true)) {
            throw new \LogicException('Unknown role.');
        }

        $this->roles[] = $role;
        $this->roles = array_values(array_unique($this->roles));
    }

    public function deleteRole(string $role): void {
        $this->roles = array_values(array_diff($this->roles, [$role]));
    }

    /** @return list<string> */
    private function getAllRoles(): array {
        return [self::ROLE_USER, self::ROLE_ADMIN];
    }
}
