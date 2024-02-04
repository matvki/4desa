<?php
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private $id;

    #[ORM\Column(type: "string", length: 255)]
    private $firstName;

    #[ORM\Column(type: "string", length: 255)]
    private $lastName;

    #[ORM\Column(type: "string", length: 255, unique: true)]
    private $username;

    #[ORM\Column(type: "string", length: 255, unique: true)]
    private $email;

    #[ORM\Column(type: "text", nullable: true)]
    private $description;

    #[ORM\OneToMany(targetEntity: Follow::class, mappedBy: "follower")]
    private $following;

    #[ORM\OneToMany(targetEntity: Follow::class, mappedBy: "user")]
    private $followers;

    public function __construct($id, $firstName, $lastName, $username, $email, $description, $following, $followers)
    {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->username = $username;
        $this->email = $email;
        $this->description = $description;
        $this->following = $following;
        $this->followers = $followers;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return Follow[]
     */
    public function getFollowing(): array
    {
        return $this->following;
    }

    public function setFollowing(ArrayCollection $following): void
    {
        $this->following = $following;
    }

    /**
     * @return Follow[]
     */
    public function getFollowers(): array
    {
        return $this->followers;
    }

    public function setFollowers(ArrayCollection $followers): void
    {
        $this->followers = $followers;
    }
}