<?php

namespace App\Entity;

use App\Repository\FollowRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FollowRepository::class)]
class Follow
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'followed')]
    #[ORM\JoinColumn(nullable: false)]
    private Account $followed;

    #[ORM\ManyToOne(inversedBy: 'follows')]
    #[ORM\JoinColumn(nullable: false)]
    private Account $follower;

    public function getId(): int
    {
        return $this->id;
    }

    public function getFollowed(): Account
    {
        return $this->followed;
    }

    public function setFollowed(Account $followed): static
    {
        $this->followed = $followed;

        return $this;
    }

    public function getFollower(): Account
    {
        return $this->follower;
    }

    public function setFollower(Account $follower): static
    {
        $this->follower = $follower;

        return $this;
    }
}
