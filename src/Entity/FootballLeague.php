<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\Constraints as AcmeAssert;
use App\Validator\Constraints\CheckUniquename;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FootballLeagueRepository")
 */
class FootballLeague
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    public $id;

    /**
     * @Assert\NotBlank
     * @Assert\Length(min=3)
     * @Assert\Length(max=30)
     * @AcmeAssert\CheckUniquename
     * @ORM\Column(type="string", length=255)
     */
    public $name;

    /**
     * @ORM\Column(type="datetime")
     */
    public $created_at;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCreatedAt(): ?DateTime
    {
        return $this->created_at;
    }

    public function setCreatedAt(DateTime $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }
}
