<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Post
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length="255")
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="posts")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="Media", mappedBy="post")
     */
    private $medias;

    /**
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="post")
     */
    private $comments;

    /**
     * @param $id
     * @param $content
     * @param $user
     * @param $medias
     * @param $comments
     */
    public function __construct($id, $content, $user, $medias, $comments)
    {
        $this->id       = $id;
        $this->content  = $content;
        $this->user     = $user;
        $this->medias   = $medias;
        $this->comments = $comments;
    }


    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return User
     */
    public function getUser(): User
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
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * @return Media[]
     */
    public function getMedias(): array
    {
        return $this->medias;
    }

    /**
     * @return Comments[]
     */
    public function getComments(): array
    {
        return $this->comments;
    }
}