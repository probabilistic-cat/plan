<?php

namespace App\Entity;

/**
 * User
 */
class User implements \Symfony\Component\Security\Core\User\UserInterface
{
    /**
     * @var integer
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
     * @var boolean
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
     * @return integer
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set userName
     *
     * @param string $userName
     *
     * @return User
     */
    public function setUserName($userName)
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
     *
     * @return User
     */
    public function setUserPassword($userPassword)
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
     *
     * @return User
     */
    public function setUserPlainPassword($userPlainPassword)
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
     *
     * @return User
     */
    public function setUserMail($userMail)
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
     * @param boolean $userActive
     *
     * @return User
     */
    public function setUserActive($userActive)
    {
        $this->userActive = $userActive;

        return $this;
    }

    /**
     * Get userActive
     *
     * @return boolean
     */
    public function getUserActive()
    {
        return $this->userActive;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return User
     */
    public function setCreated($created)
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
     *
     * @return User
     */
    public function setModified($modified)
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
     * @return string
     */
    public function serialize()
    {
        return serialize(array(
            $this->userId,
            $this->userName,
            $this->userPassword,
        ));
    }

    /**
     * Unserialize
     * @param mixed $serialized
     */
    public function unserialize($serialized)
    {
        list (
            $this->userId,
            $this->userName,
            $this->userPassword,
        ) = unserialize($serialized);
    }

    /**
     * Alias for getUserPassword
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
     * @return array
     */
    public function getRoles()
    {
        return array('ROLE_USER');
    }

    /**
     * Erase credentials
     */
    public function eraseCredentials() {}
}
