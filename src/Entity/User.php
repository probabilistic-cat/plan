<?php

namespace App\Entity;

/**
 * User
 */
class User implements \Symfony\Component\Security\Core\User\UserInterface
{
    /**
     * @var int
     */
    private $userId;

    /**
     * @var string
     */
    private $userName;

    /**
     * @var string
     */
    private $userPassword;

    /**
     * @var string
     */
    private $userPlainPassword;

    /**
     * @var string
     */
    private $userMail;

    /**
     * @var bool
     */
    private $userActive = 0;

    /**
     * @var \DateTime
     */
    private $created = '1999-12-31 21:00:00';

    /**
     * @var \DateTime
     */
    private $modified;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->created = new \DateTime($this->created);
    }

    /**
     * Get userId
     *
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set userName
     *
     * @param string $userName
     */
    public function setUserName($userName): self
    {
        $this->userName = $userName;

        return $this;
    }

    /**
     * Get userName
     *
     * @return string
     */
    public function getUserName()
    {
        return $this->userName;
    }

    /**
     * Set userPassword
     *
     * @param string $userPassword
     */
    public function setUserPassword($userPassword): self
    {
        $this->userPassword = $userPassword;

        return $this;
    }

    /**
     * Get userPassword
     *
     * @return string
     */
    public function getUserPassword()
    {
        return $this->userPassword;
    }

    /**
     * Set userPlainPassword
     *
     * @param string $userPlainPassword
     */
    public function setUserPlainPassword($userPlainPassword): self
    {
        $this->userPlainPassword = $userPlainPassword;

        return $this;
    }

    /**
     * Get userPlainPassword
     *
     * @return string
     */
    public function getUserPlainPassword()
    {
        return $this->userPlainPassword;
    }

    /**
     * Set userMail
     *
     * @param string $userMail
     */
    public function setUserMail($userMail): self
    {
        $this->userMail = $userMail;

        return $this;
    }

    /**
     * Get userMail
     *
     * @return string
     */
    public function getUserMail()
    {
        return $this->userMail;
    }

    /**
     * Set userActive
     *
     * @param bool $userActive
     */
    public function setUserActive($userActive): self
    {
        $this->userActive = $userActive;

        return $this;
    }

    /**
     * Get userActive
     *
     * @return bool
     */
    public function getUserActive()
    {
        return $this->userActive;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     */
    public function setCreated($created): self
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set modified
     *
     * @param \DateTime $modified
     */
    public function setModified($modified): self
    {
        $this->modified = $modified;

        return $this;
    }

    /**
     * Get modified
     *
     * @return \DateTime
     */
    public function getModified()
    {
        return $this->modified;
    }

    /**
     * Serialize
     */
    public function serialize(): string
    {
        return serialize([
            $this->userId,
            $this->userName,
            $this->userPassword,
        ]);
    }

    /**
     * Unserialize
     */
    public function unserialize($serialized): void
    {
        [$this->userId, $this->userName, $this->userPassword] = unserialize($serialized);
    }

    /**
     * Alias for getUserPassword
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->getUserPassword();
    }

    /**
     * Get salt
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * Get roles
     *
     * @return array
     */
    public function getRoles()
    {
        return ['ROLE_USER'];
    }

    /**
     * Erase credentials
     */
    public function eraseCredentials()
    {
    }
}
