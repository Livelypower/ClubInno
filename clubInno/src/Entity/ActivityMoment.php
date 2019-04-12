<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ActivityMomentRepository")
 */
class ActivityMoment
{
    /**
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context, $payload)
    {
        // somehow you have an array of "fake names"
        $firstDate = $this->getStartDate();
        $secondDate = $this->getEndDate();
        $firstTime = $this->getStartTime();
        $secondTime = $this->getEndTime();

        if($firstDate != null && $secondDate != null){
            if ($firstDate > $secondDate) {
                $context->buildViolation('end date must be later than start date')
                    ->atPath('endDate')
                    ->addViolation();
            }
        }

        if($firstTime != null && $secondTime != null){
            if ($firstTime > $secondTime && $firstDate === $secondDate) {
                $context->buildViolation('If on the same day, end time must be later than start time')
                    ->atPath('endTime')
                    ->addViolation();
            }
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
    private $name;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $location;


    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\ActivityGroup", inversedBy="activityMoments")
     * @Assert\NotBlank()
     */
    private $activityGroups;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank()
     * @Assert\Date()
     */
    private $startDate;

    /**
     * @ORM\Column(type="time")
     * @Assert\NotBlank()
     */
    private $startTime;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Assert\Date()
     */
    private $endDate;

    /**
     * @ORM\Column(type="time", nullable=true)
     */
    private $endTime;

    public function __construct()
    {
        $this->activityGroups = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): self
    {
        $this->location = $location;

        return $this;
    }



    /**
     * @return Collection|ActivityGroup[]
     */
    public function getActivityGroups(): Collection
    {
        return $this->activityGroups;
    }

    public function addActivityGroup(ActivityGroup $activityGroup): self
    {
        if (!$this->activityGroups->contains($activityGroup)) {
            $this->activityGroups[] = $activityGroup;
        }

        return $this;
    }

    public function removeActivityGroup(ActivityGroup $activityGroup): self
    {
        if ($this->activityGroups->contains($activityGroup)) {
            $this->activityGroups->removeElement($activityGroup);
        }

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate = null): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getStartTime(): ?\DateTimeInterface
    {
        return $this->startTime;
    }

    public function setStartTime(\DateTimeInterface $startTime = null): self
    {
        $this->startTime = $startTime;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(?\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getEndTime(): ?\DateTimeInterface
    {
        return $this->endTime;
    }

    public function setEndTime(?\DateTimeInterface $endTime): self
    {
        $this->endTime = $endTime;

        return $this;
    }
}
