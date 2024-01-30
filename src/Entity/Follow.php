<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Follow
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="following")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="followers")
     * @ORM\JoinColumn(name="follower_id", referencedColumnName="id")
     */
    private $follower;

    /**
     * @param $id
     * @param $user
     * @param $follower
     */
    public function __construct($id, $user, $follower)
    {
        $this->id       = $id;
        $this->user     = $user;
        $this->follower = $follower;
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
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
