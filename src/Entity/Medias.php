<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Medias
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Posts", inversedBy="medias")
     * @ORM\JoinColumn(name="post_id", referencedColumnName="id")
     */
    private $post;

    // see what we need to get some images or video from azure
}