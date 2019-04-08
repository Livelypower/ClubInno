<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ActivityGroupRepository")
 */
class ActivityGroup
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
     * Many ActivityGroups have one Activity. This is the owning side.
     * @ORM\ManyToOne(targetEntity="Activity", inversedBy="activityGroups")
     * @ORM\JoinColumn(name="actvity_id", referencedColumnName="id")
     */
    private $activity;

    /**
     * Many ActivityGroups have Many Users.
     * @ORM\ManyToMany(targetEntity="User", inversedBy="activityGroups")
     */
    private $users;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\ActivityMoment", mappedBy="activityGroups")
     */
    private $activityMoments;

    public function __construct()
    {
        $this->activityMoments = new ArrayCollection();
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

    /**
     * @return mixed
     */
    public function getActivity()
    {
        return $this->activity;
    }

    /**
     * @param mixed $activity
     */
    public function setActivity($activity): void
    {
        $this->activity = $activity;
    }

    /**
     * @return mixed
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @param mixed $users
     */
    public function setUsers($users): void
    {
        $this->users = $users;
    }

    /**
     * @return Collection|ActivityMoment[]
     */
    public function getActivityMoments(): Collection
    {
        return $this->activityMoments;
    }

    public function addActivityMoment(ActivityMoment $activityMoment): self
    {
        if (!$this->activityMoments->contains($activityMoment)) {
            $this->activityMoments[] = $activityMoment;
            $activityMoment->addActivityGroup($this);
        }

        return $this;
    }

    public function removeActivityMoment(ActivityMoment $activityMoment): self
    {
        if ($this->activityMoments->contains($activityMoment)) {
            $this->activityMoments->removeElement($activityMoment);
            $activityMoment->removeActivityGroup($this);
        }

        return $this;
    }
}
