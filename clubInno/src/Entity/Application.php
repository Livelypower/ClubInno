<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $motivationLetterPath;

    /**
     * Many Applications have Many Activities.
     * @ORM\ManyToMany(targetEntity="Activity", inversedBy="applications")
     */
    private $activities;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="applications")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function __construct(){
        $this->activities = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return mixed
     */
    public function getActivities()
    {
        return $this->activities;
    }


   public function setActivities($activities){
        $this->activities = $activities;
   }

   public function getUser(): ?User
   {
       return $this->user;
   }

   public function setUser(?User $user): self
   {
       $this->user = $user;

       return $this;
   }




}
