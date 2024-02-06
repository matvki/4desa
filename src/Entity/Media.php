<?php

namespace App\Entity;

use App\Repository\MediaRepository;
use Doctrine\DBAL\Types\BlobType;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Attributes as OA;
use Symfony\Component\Serializer\Attribute\Groups;

#[OA\Schema(
    properties: [
        new OA\Property(
            property: 'id',
            type: 'integer',
            description: 'Media id',
            example: 1
        ),
        new OA\Property(
            property: 'picture',
            type: 'string',
            description: 'Media picture',
            example: 'base64:xxxxx'
        ),
        new OA\Property(
            property: 'post',
            type: Post::class,
            description: 'Post entity',
            example: 1
        ),
    ]
)]
#[ORM\Entity(repositoryClass: MediaRepository::class)]
class Media
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'media', cascade: ['persist', 'remove'])]
    private Post $post;

    #[ORM\Column(type: "text")]
    #[Groups(["account_details"])]
    private string $picture;

    public function getId(): int
    {
        return $this->id;
    }

    public function getPost(): Post
    {
        return $this->post;
    }

    public function setPost(Post $post): static
    {
        $this->post = $post;

        return $this;
    }

    public function getPicture(): string
    {
        return $this->picture;
    }

    public function setPicture($picture): static
    {
        $this->picture = $picture;

        return $this;
    }
}
