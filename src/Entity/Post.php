<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Attributes as OA;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: PostRepository::class)]
#[OA\Schema(
    properties: [
        
    ]
)]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'string')]
    #[Groups(["account_details"])]
    private string $description;

    #[ORM\ManyToOne(inversedBy: 'posts')]
    #[ORM\JoinColumn(nullable: false)]
    private Account $belongsTo;

    #[ORM\OneToOne(mappedBy: 'post', cascade: ['persist', 'remove'])]
    #[Groups(["account_details"])]
    private ?Media $media = null;

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
}
