<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Comments
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Posts", inversedBy="comments")
     * @ORM\JoinColumn(name="post_id", referencedColumnName="id")
     */
    private $post;

    /**
     * @ORM\ManyToOne(targetEntity="Users")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\Column(type="text")
     */
    private $text;

    /**
     * @param $id
     * @param $post
     * @param $user
     * @param $text
     */
    public function __construct($id, $post, $user, $text)
    {
        $this->id   = $id;
        $this->post = $post;
        $this->user = $user;
        $this->text = $text;
    }


    /**
     * @return Posts
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * @param Posts $post
     */
    public function setPost(Posts $post): void
    {
        $this->post = $post;
    }

    /**
     * @return Users
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param Users $user
     */
    public function setUser(Users $user): void
    {
        $this->user = $user;
    }

    /**
     * @return Texts
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param Texts $text
     */
    public function setText(Texts $text): void
    {
        $this->text = $text;
    }
}
