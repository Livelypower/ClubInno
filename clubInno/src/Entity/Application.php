<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ApplicationRepository")
 */
class Application
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $userId;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $motivationLetterPath;

    /**
     * Many Applications have Many Activities.
     * @ORM\ManyToMany(targetEntity="Activity", inversedBy="applications")
     * @ORM\JoinTable(name="applications_activities")
     */
    private $activities;

    public function __construct(){
        $this->activities = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getMotivationLetterPath(): ?string
    {
        return $this->motivationLetterPath;
    }

    public function setMotivationLetterPath(string $motivationLetterPath): self
    {
        $this->motivationLetterPath = $motivationLetterPath;

        return $this;
    }
}
