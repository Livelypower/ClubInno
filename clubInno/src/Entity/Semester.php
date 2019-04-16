<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;


/**
 * @ORM\Entity(repositoryClass="App\Repository\SemesterRepository")
 */
class Semester
{
    /**
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context, $payload)
    {
        // somehow you have an array of "fake names"
        $startYear = $this->getStartYear();
        $endYear = $this->getEndYear();

        if($startYear+1 != $endYear){
            $context->buildViolation("'Fin' doit être exactement une année plus tard que 'Début'.")
                ->atPath('endYear')
                ->addViolation();
        }
    }

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $startYear;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $endYear;

    /**
     * One Semester has many Activities. This is the inverse side.
     * @ORM\OneToMany(targetEntity="Activity", mappedBy="semester")
     */
    private $activities;

    private $stringified;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active;

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

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

}
