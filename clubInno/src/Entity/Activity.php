<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ActivityRepository")
 */
class Activity
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="blob", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="integer")
     */
    private $maxAmountStudents;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $registrationDeadline;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $active;

    /**
     * Many Applications have Many Activities.
     * @ORM\ManyToMany(targetEntity="Application", inversedBy="activities")
     */
    private $applications;

    public function __construct()
    {
        $this->applications = new \Doctrine\Common\Collections\ArrayCollection();
    }

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

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getMaxAmountStudents(): ?int
    {
        return $this->maxAmountStudents;
    }

    public function setMaxAmountStudents(int $maxAmountStudents): self
    {
        $this->maxAmountStudents = $maxAmountStudents;

        return $this;
    }

    public function getRegistrationDeadline(): ?\DateTimeInterface
    {
        return $this->registrationDeadline;
    }

    public function setRegistrationDeadline(?\DateTimeInterface $registrationDeadline): self
    {
        $this->registrationDeadline = $registrationDeadline;

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(?bool $active): self
    {
        $this->active = $active;

        return $this;
    }
}
