<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastName;

    /**
     * One user has many blogPosts. This is the inverse side.
     * @ORM\OneToMany(targetEntity="BlogPost", mappedBy="user")
     */
    private $blogPosts;

    /**
     * One user has many Comments. This is the inverse side.
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="user")
     */
    private $comments;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Orientation;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Application", mappedBy="user")
     */
    private $applications;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Activity", inversedBy="users")
     */
    private $registrations;

    /**
     * Many Users have Many ActivityGroups.
     * @ORM\ManyToMany(targetEntity="ActivityGroup", mappedBy="users")
     */
    private $activityGroups;


    public function __construct()
    {
        $this->blogPosts = new \Doctrine\Common\Collections\ArrayCollection();
        $this->comments = new \Doctrine\Common\Collections\ArrayCollection();
        $this->applications = new ArrayCollection();
        $this->registrations = new ArrayCollection();
        $this->activityGroups = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getOrientation(): ?string
    {
        return $this->Orientation;
    }

    public function setOrientation(?string $Orientation): self
    {
        $this->Orientation = $Orientation;
    }

    /**
     * @return Collection|Application[]
     */
    public function getApplications(): Collection
    {
        return $this->applications;
    }

    public function addApplication(Application $application): self
    {
        if (!$this->applications->contains($application)) {
            $this->applications[] = $application;
            $application->setUser($this);
        }

        return $this;
    }

    public function removeApplication(Application $application): self
    {
        if ($this->applications->contains($application)) {
            $this->applications->removeElement($application);
            // set the owning side to null (unless already changed)
            if ($application->getUser() === $this) {
                $application->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Activity[]
     */
    public function getRegistrations(): Collection
    {
        return $this->registrations;
    }

    public function setRegistrations($registrations)
    {
        $this->registrations = $registrations;
    }

    public function addRegistration(Activity $registration): self
    {
        if (!$this->registrations->contains($registration)) {
            $this->registrations[] = $registration;
        }

        return $this;
    }

    public function removeRegistration(Activity $registration): self
    {
        if ($this->registrations->contains($registration)) {
            $this->registrations->removeElement($registration);
        }

        return $this;
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

    public function removeActivityGroup(ActivityGroup $group)
    {
        if ($this->activityGroups->contains($group)) {
            $this->activityGroups->removeElement($group);
        }

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
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @param mixed $comments
     */
    public function setComments($comments): void
    {
        $this->comments = $comments;
    }





}
