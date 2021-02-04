<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Table(name="users")
 * @ORM\Entity
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="guid")
     * @ORM\GeneratedValue(strategy="UUID")
     */
    private $id;
    /**
     * @ORM\Column(type="string", length=25, unique=true)
    */
    private $username;
    /**
     * @ORM\Column(type="string", length=255)
    */
    private $password;

    /**
     * @ORM\Column(type="string", length=45)
    */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * User constructor.
    * @param $username
    */
    public function __construct($username)
    {
        $this->username = $username;
    }

    /**
     * @return string
    */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
    */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
    */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
    */
    public function setUsername($username): void
    {
        $this->username = $username;
    }

    /**
     * @return string|null
    */
    public function getSalt()
    {
        return null;
    }

    /**
     * @return string|null
    */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
    */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }
    /**
     * @return mixed
    */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
    */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        return $this;
    }

    /**
     * @return array|string[]
    */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    public function eraseCredentials(): void
    {
    }
}
