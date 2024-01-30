<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Texts
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Post", inversedBy="texts")
     * @ORM\JoinColumn(name="post_id", referencedColumnName="id")
     */
    private $post;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $text;

    /**
     * @param $id
     * @param $post
     * @param $text
     */
    public function __construct($id, $post, $text)
    {
        $this->id   = $id;
        $this->post = $post;
        $this->text = $text;
    }


    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
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
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText(string $text): void
    {
        $this->text = $text;
    }
}