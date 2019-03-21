<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CommentRepository")
 */
class Comment
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $body;

    /**
     * @ORM\Column(type="datetime")
     */
    private $datetime;

    /**
     * One Comment can have Many child Comments.
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="parentComments")
     */
    private $childComments;

    /**
     * Many child Comments have One parent Comment.
     * @ORM\ManyToOne(targetEntity="Comment", inversedBy="childComments")
     * @ORM\JoinColumn(name="parentComment_id", referencedColumnName="id")
     */
    private $parentComment;

    /**
     * Many comments have one user. This is the owning side.
     * @ORM\ManyToOne(targetEntity="User", inversedBy="comments")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * Many comments have one blogpost. This is the owning side.
     * @ORM\ManyToOne(targetEntity="BlogPost", inversedBy="comments")
     * @ORM\JoinColumn(name="BlogPost_id", referencedColumnName="id")
     */
    private $blogPost;


    public function __construct() {
        $this->childComments = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(string $body): self
    {
        $this->body = $body;

        return $this;
    }

    public function getDatetime(): ?\DateTimeInterface
    {
        return $this->datetime;
    }

    public function setDatetime(\DateTimeInterface $datetime): self
    {
        $this->datetime = $datetime;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getChildComments()
    {
        return $this->childComments;
    }

    /**
     * @param mixed $childComments
     */
    public function setChildComments($childComments): void
    {
        $this->childComments = $childComments;
    }

    /**
     * @return mixed
     */
    public function getParentComment()
    {
        return $this->parentComment;
    }

    /**
     * @param mixed $parentComment
     */
    public function setParentComment($parentComment): void
    {
        $this->parentComment = $parentComment;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user): void
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getBlogPost()
    {
        return $this->blogPost;
    }

    /**
     * @param mixed $blogPost
     */
    public function setBlogPost($blogPost): void
    {
        $this->blogPost = $blogPost;
    }

}
