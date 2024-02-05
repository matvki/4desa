<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostRepository::class)]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::BLOB)]
    private string $description;

    #[ORM\ManyToOne(inversedBy: 'posts')]
    #[ORM\JoinColumn(nullable: false)]
    private Account $belongsTo;

    #[ORM\OneToOne(mappedBy: 'post', cascade: ['persist', 'remove'])]
    private ?Media $media = null;

    #[ORM\OneToMany(mappedBy: 'post', targetEntity: Comment::class, orphanRemoval: true)]
    private Collection $comments;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getBelongsTo(): Account
    {
        return $this->belongsTo;
    }

    public function setBelongsTo(Account $belongsTo): static
    {
        $this->belongsTo = $belongsTo;

        return $this;
    }

    public function getMedia(): ?Media
    {
        return $this->media;
    }

    public function setMedia(?Media $media): static
    {
        // unset the owning side of the relation if necessary
        if ($media === null && $this->media !== null)
            $this->media->setPost(null);

        // set the owning side of the relation if necessary
        if ($media !== null && $media->getPost() !== $this)
            $media->setPost($this);

        $this->media = $media;

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): static
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setPost($this);
        }

        return $this;
    }

//    public function removeComment(Comment $comment): static
//    {
//        if ($this->comments->removeElement($comment) && $comment->getPost() === $this)
//                $comment->setPost(null);
//
//        return $this;
//    }
}
