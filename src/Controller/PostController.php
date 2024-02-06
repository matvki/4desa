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
        description: 'User\'s Picture for the post in base64',
        required: false
    )]
    #[Route('/api/post/create', name: 'create_post', methods: ['POST'])]
    public function createPost(Request $request): JsonResponse
    {
        $currentUser = $this->getUser();
        if ($currentUser === null) // user not connected
            return $this->json(['message' => 'Request not found'], Response::HTTP_NOT_FOUND); // to protect api route from attack

        $data = json_decode($request->getContent(), true);
        $post = new Post();

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
        if ($currentUser === null) // user not connected
            return $this->json(['message' => 'Request not found'], Response::HTTP_NOT_FOUND); // to protect api route from attack

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
        description: 'Change for user\'s post description',
        required: false,
    )]
    #[OA\Parameter(
        name: 'picture',
        in: 'query',
        description: 'Change for user\'s Picture for the post in base64',
        required: false,
    )]
    #[OA\Parameter(
        name: 'id',
        in: 'path',
        description: 'Post id',
        required: true
    )]
    #[Route('/api/post/update/{id}', name: 'update_post', methods: ['PUT'])]
    public function updatePost(Post $post, Request $request): JsonResponse
    {
        $data        = json_decode($request->getContent(), true);
        $currentUser = $this->getUser();

        if ($currentUser === null) // user not connected
            return $this->json(['message' => 'Request not found'], Response::HTTP_NOT_FOUND); // to protect api route from attack
        if ($post->getBelongsTo() !== $currentUser)
            return $this->json(['message' => 'Unauthorized'], Response::HTTP_FORBIDDEN);

        if (isset($data['description']))
            $post->setDescription($data['description']);
        if (isset($data['picture'])) // Handling media update (assuming $data['picture'] is a base64 image)
            $post->getMedia()->setPicture($data['picture']);

        try {
            $this->emi->flush();
        } catch (Exception $exception) {
            return $this->json(['message' => $exception->getMessage()], 500);
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
    public function deletePost(Post $post): JsonResponse
    {
        $currentUser = $this->getUser();

        if ($currentUser === null) // user not connected
            return $this->json(['message' => 'Request not found'], Response::HTTP_NOT_FOUND); // to protect api route from attack
        if ($post->getBelongsTo() !== $currentUser)
            return $this->json(['message' => 'Unauthorized'], Response::HTTP_FORBIDDEN);

        try {
            $this->emi->remove($post);
            $this->emi->flush();
        } catch (Exception $exception) {
            return $this->json(['message' => $exception->getMessage()], 500);
        }

        return $this->json(['message' => 'Post deleted successfully']);
    }
}
