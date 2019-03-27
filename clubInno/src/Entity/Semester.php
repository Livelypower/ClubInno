<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SemesterRepository")
 */
class Semester
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
    private $startYear;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $endYear;

    /**
     * One Semester has many Activities. This is the inverse side.
     * @ORM\OneToMany(targetEntity="Activity", mappedBy="semester")
     */
    private $activities;

    private $stringified;

    public function __construct() {
        $this->activities = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartYear(): ?string
    {
        return $this->startYear;
    }

    public function setStartYear(string $startYear): self
    {
        $this->startYear = $startYear;

        return $this;
    }

    public function getEndYear(): ?string
    {
        return $this->endYear;
    }

    public function setEndYear(string $endYear): self
    {
        $this->endYear = $endYear;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getStringified()
    {
        return $this->startYear . " - " . $this->endYear;
    }

}
