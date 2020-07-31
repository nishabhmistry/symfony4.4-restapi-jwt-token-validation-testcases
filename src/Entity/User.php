<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\Constraints as AcmeAssert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @UniqueEntity("username")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank
     * @Assert\Length(min=3)
     * @Assert\Length(max=20)
     * @ORM\Column(type="string", length=255)
     */
    public $firstname;

    /**
     * @Assert\NotBlank
     * @Assert\Length(min=3)
     * @Assert\Length(max=20)
     * @ORM\Column(type="string", length=255)
     */
    public $lastname;

    /**
     * @Assert\NotBlank
     * @Assert\Email
     * @ORM\Column(type="string", length=255)
     */
    public $email;

    /**
     * @Assert\NotBlank
     * @Assert\Length(min=3)
     * @Assert\Length(max=20)
     * @ORM\Column(type="string", length=255)
     */
    public $username;

    /**
     * @Assert\NotBlank
     * @Assert\Length(min=3)
     * @Assert\Length(max=15)
     * @ORM\Column(type="text")
     */
    public $password;

    /**
     * @ORM\Column(type="boolean")
     */
    public $active;

    public function __construct($username = "", $firstname = "", $lastname = "", $email = "")
    {
        $this->active = true;
        $this->username = $username;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->email = $email;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {

        $this->email = $email;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getRoles()
    {
        return array('ROLE_USER');
    }

    public function eraseCredentials()
    {
    }

    public function getSalt()
    {
        return null;
    }

}
