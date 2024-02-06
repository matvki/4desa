<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Media;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[OA\Tag('Post')]
class PostController extends AbstractController
{
    private EntityManagerInterface $emi;

    public function __construct(
        EntityManagerInterface $emi
    ) {
        $this->emi = $emi;
    }

    #[OA\Response(
        response: 200,
        description: 'Return a json array with a validation message',
    )]
    #[OA\Parameter(
        name: 'description',
        in: 'query',
        description: 'User\'s post description',
        required: true
    )]
    #[OA\Parameter(
        name: 'picture',
        in: 'query',
        description: 'User\'s Picture for the post',
    )]
    #[Route('/api/post/create', name: 'create_post', methods: ['POST'])]
    public function createPost(Request $request): JsonResponse
    {
        $data        = json_decode($request->getContent(), true);
        $currentUser = $this->getUser();
        $post        = new Post();

        $post->setDescription($data['description']);
        $post->setBelongsTo($currentUser);

        try {
            $this->emi->persist($post);
            $this->emi->flush();
        } catch (Exception $exception) {
            return $this->json(['message' => $exception->getMessage()], 500);
        }

        // Handling media creation (assuming $data['picture'] is a base64 image)
        if (isset($data['picture'])) {
            $media = new Media();
            $media->setPost($post);
            $media->setPicture($data['picture']);

            try {
                $this->emi->persist($media);
                $this->emi->flush();
            } catch (Exception $exception) {
                return $this->json(['message' => $exception->getMessage()], 500);
            }
        }

        return $this->json(['message' => 'Post created successfully'], Response::HTTP_CREATED);
    }

    #[OA\Response(
        response: 200,
        description: 'Return a json array with the content of the post and the user who created it',
    )]
    #[OA\Parameter(
        name: 'id',
        in: 'path',
        description: 'Post id',
        required: true
    )]
    #[Route('/api/post/get/{id}', name: 'get_post', methods: ['GET'])]
    public function getPost(Post $post): JsonResponse
    {
        $currentUser = $this->getUser();

        if ($post->getBelongsTo()->isPrivate()) {
            if ($post->getBelongsTo() !== $currentUser )
                return $this->json(['message' => "Can't access to user Post, user is in private mod."], Response::HTTP_FORBIDDEN);
        }

        $media    = $post->getMedia();
        $postData = [
            'id'          => $post->getId(),
            'description' => $post->getDescription(),
            'user_id'     => $post->getBelongsTo()->getId(),
            'media'       => $media?->getPicture(),
        ];

        return $this->json(["post" => $postData]);
    }

    #[OA\Response(
        response: 200,
        description: 'Return a json array with a validation message',
    )]
    #[OA\Parameter(
        name: 'description',
        in: 'query',
        description: 'User\'s post description',
        required: true,
    )]
    #[OA\Parameter(
        name: 'picture',
        in: 'query',
        description: 'User\'s Picture for the post',
        required: true,
    )]
    #[OA\Parameter(
        name: 'id',
        in: 'path',
        description: 'Post id',
        required: true
    )]
    #[Route('/api/post/update/{id}', name: 'update_post', methods: ['PATCH'])]
    public function updatePost(Post $post, Request $request, EntityManagerInterface $emi): JsonResponse
    {
        $data        = json_decode($request->getContent(), true);
        $currentUser = $this->getUser();

        if ($post->getBelongsTo() !== $currentUser)
            return $this->json(['message' => 'Unauthorized'], Response::HTTP_FORBIDDEN);

        $post->setDescription($data['description']);

        try {
            $this->emi->flush();
        } catch (Exception $exception) {
            return $this->json(['message' => $exception->getMessage()], 500);
        }

        // Handling media update (assuming $data['picture'] is a base64 image)
        if (isset($data['picture'])) {
            // Clear existing media
            $previousPicture = $post->getMedia();

            try {
                $emi->remove($previousPicture);
                $this->emi->flush();
            } catch (Exception $exception) {
                return $this->json(['message' => $exception->getMessage()], 500);
            }

            $media = new Media();
            $media->setPost($post);
            $media->setPicture($data['picture']);

            try {
                $emi->persist($media);
                $this->emi->flush();
            } catch (Exception $exception) {
                return $this->json(['message' => $exception->getMessage()], 500);
            }
        }

        return $this->json(['message' => 'Post updated successfully']);
    }

    #[OA\Response(
        response: 200,
        description: 'Return a json array with a validation message',
    )]
    #[OA\Parameter(
        name: 'id',
        in: 'path',
        description: 'Post id',
        required: true
    )]
    #[Route('/api/post/delete/{id}', name: 'delete_post', methods: ['DELETE'])]
    public function deletePost(Post $post, EntityManagerInterface $emi): JsonResponse
    {
        $currentUser = $this->getUser();
        
        if ($post->getBelongsTo() !== $currentUser)
            return $this->json(['message' => 'Unauthorized'], Response::HTTP_FORBIDDEN);

        try {
            $emi->remove($post);
            $this->emi->flush();
        } catch (Exception $exception) {
            return $this->json(['message' => $exception->getMessage()], 500);
        }
        return $this->json(['message' => 'Post deleted successfully']);
    }
}
