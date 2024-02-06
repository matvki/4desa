<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Media
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private $id;

    #[ORM\ManyToOne(targetEntity: Post::class, inversedBy: "medias")]
    #[ORM\JoinColumn(name: "post_id", referencedColumnName: "id")]
    private $post;

    // Ajoutez ici les propriétés nécessaires pour obtenir des images ou des vidéos depuis Azure
}