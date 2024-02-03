<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Follow
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private $id;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "following")]
    #[ORM\JoinColumn(name: "user_id", referencedColumnName: "id")]
    private $user;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "followers")]
    #[ORM\JoinColumn(name: "follower_id", referencedColumnName: "id")]
    private $follower;

    public function __construct($id, $user, $follower)
    {
        $this->id = $id;
        $this->user = $user;
        $this->follower = $follower;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    /**
     * @return User[]
     */
    public function getFollower(): array
    {
        return $this->follower;
    }

    /**
     * @param User[] $follower
     */
    public function setFollower(array $follower): void
    {
        $this->follower = $follower;
    }
}

