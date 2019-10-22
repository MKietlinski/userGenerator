<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @UniqueEntity(fields={"email"})
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active = false;

    /**
     * @Assert\Email
     * @Assert\NotBlank
     * @ORM\Column(type="string", unique=true)
     */
    private $email;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="string", length=11)
     */
    private $pesel;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="string")
     */
    private $firstName;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="string")
     */
    private $lastName;

    /**
     * @ORM\Column(type="array")
     */
    private $programmingLanguages = [];

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPesel(): ?string
    {
        return $this->pesel;
    }

    public function setPesel(string $pesel): void
    {
        $this->pesel = $pesel;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getProgrammingLanguages(): array
    {
        return $this->programmingLanguages;
    }

    public function setProgrammingLanguages(array $programmingLanguages): void
    {
        $this->programmingLanguages = $programmingLanguages;
    }
}