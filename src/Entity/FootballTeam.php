<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\Constraints as AcmeAssert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use App\Validator\Constraints\CheckUniqueTeamname;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FootballTeamRepository")
 */
class FootballTeam
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    public $id;

    /**
     * @ORM\Column(type="integer")
     */
    public $leagueid;

    /**
     * @Assert\NotBlank
     * @Assert\Length(min=3)
     * @Assert\Length(max=30)
     * @AcmeAssert\CheckUniqueTeamname
     * @ORM\Column(type="string", length=255)
     */
    public $name;

    /**
     * @Assert\NotBlank
     * @Assert\Length(min=3)
     * @Assert\Length(max=30)
     * @ORM\Column(type="string", length=255)
     */
    public $strip;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLeagueid(): ?int
    {
        return $this->leagueid;
    }

    public function setLeagueid(int $leagueid): self
    {
        $this->leagueid = $leagueid;

        return $this;
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

    public function getStrip(): ?string
    {
        return $this->strip;
    }

    public function setStrip(string $strip): self
    {
        $this->strip = $strip;

        return $this;
    }
}
