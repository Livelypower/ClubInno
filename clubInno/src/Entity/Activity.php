<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @ORM\Column(type="string", length=255, nullable=true)
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

    /**
     * Many Activities have Many Tags.
     * @ORM\ManyToMany(targetEntity="Tag", inversedBy="activities")
     */
    private $tags;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Image()
     */
    private $mainImage;


    public function __construct()
    {
        $this->applications = new \Doctrine\Common\Collections\ArrayCollection();
        $this->tags = new \Doctrine\Common\Collections\ArrayCollection();
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

    public function getTags()
    {
        return $this->tags;
    }

    public function getMainImage(): ?string
    {
        return $this->mainImage;
    }

    public function setMainImage(?string $mainImage): self
    {
        $this->mainImage = $mainImage;

        return $this;
    }


}
