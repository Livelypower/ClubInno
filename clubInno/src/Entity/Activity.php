<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $active;

    /**
     * Many Applications have Many Activities.
     * @ORM\ManyToMany(targetEntity="Application", mappedBy="activities")
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

    /**
     * One activity has many blogPosts. This is the inverse side.
     * @ORM\OneToMany(targetEntity="BlogPost", mappedBy="activity")
     */
    private $blogPosts;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $files = [];

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", mappedBy="registrations")
     */
    private $users;

    /**
     * Many activities have one semester. This is the owning side.
     * @ORM\ManyToOne(targetEntity="Semester", inversedBy="activities")
     * @ORM\JoinColumn(name="semester_id", referencedColumnName="id")
     */
    private $semester;

    /**
     * One Activity has many ActivityGroups. This is the inverse side.
     * @ORM\OneToMany(targetEntity="ActivityGroup", mappedBy="activity")
     */
    private $activityGroups;

    public function __construct()
    {
        $this->applications = new \Doctrine\Common\Collections\ArrayCollection();
        $this->tags = new \Doctrine\Common\Collections\ArrayCollection();
        $this->blogPosts = new \Doctrine\Common\Collections\ArrayCollection();
        $this->users = new ArrayCollection();
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

    public function getFiles(): ?array
    {
        return $this->files;
    }

    public function setFiles(?array $files): self
    {
        $this->files = $files;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->addRegistration($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            $user->removeRegistration($this);
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSemester()
    {
        return $this->semester;
    }

    /**
     * @param mixed $semester
     */
    public function setSemester($semester): void
    {
        $this->semester = $semester;
    }

    /**
     * @return mixed
     */
    public function getApplications()
    {
        return $this->applications;
    }

    /**
     * @param mixed $applications
     */
    public function setApplications($applications): void
    {
        $this->applications = $applications;
    }

    /**
     * @return mixed
     */
    public function getBlogPosts()
    {
        return $this->blogPosts;
    }

    /**
     * @param mixed $blogPosts
     */
    public function setBlogPosts($blogPosts): void
    {
        $this->blogPosts = $blogPosts;
    }

    /**
     * @return mixed
     */
    public function getActivityGroups()
    {
        return $this->activityGroups;
    }

    /**
     * @param mixed $activityGroups
     */
    public function setActivityGroups($activityGroups): void
    {
        $this->activityGroups = $activityGroups;
    }
}
