<?php
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="Follow", mappedBy="follower")
     */
    private $following;

    /**
     * @ORM\OneToMany(targetEntity="Follow", mappedBy="user")
     */
    private $followers;

    /**
     * @ORM\OneToMany(targetEntity="Post")
     */

    /**
     * @param $id
     * @param $firstName
     * @param $lastName
     * @param $username
     * @param $email
     * @param $description
     * @param $following
     * @param $followers
     */
    public function __construct($id, $firstName, $lastName, $username, $email, $description, $following, $followers)
    {
        $this->id          = $id;
        $this->firstName   = $firstName;
        $this->lastName    = $lastName;
        $this->username    = $username;
        $this->email       = $email;
        $this->description = $description;
        $this->following   = $following;
        $this->followers   = $followers;
    }


    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName($firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName($lastName): void
    {
        $this->lastName = $lastName;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername($username): void
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description): void
    {
        $this->description = $description;
    }

    /**
     * @return Follow[] // a test
     */
    public function getFollowing(): array
    {
        return $this->following;
    }

    /**
     * @param ArrayCollection $following
     */
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

    /**
     * @param ArrayCollection $followers
     */
    public function setFollowers(ArrayCollection $followers): void
    {
        $this->followers = $followers;
    }


}
