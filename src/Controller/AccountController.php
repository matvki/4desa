<?php

namespace App\Controller;

use App\Entity\Account;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    #[Route('/account/create', name: 'create_account', methods: ['POST'])]
    public function createUser(Request $request, EntityManagerInterface $emi): JsonResponse
    {
        $data = $request->query->all();

        $account = new Account();
        $account->setEmail($data['email']);
        $account->setPassword($data['password']);
        $account->setPseudo($data['pseudo']);

        if ($data['description'] !== "")
            $account->setDescription($data['description']);
        if ($data['private'] !== "")
            $account->setPrivate($data['private']);
        if (isset($data['roles']))
            $account->setRoles([$data['roles']]);

        try {
            $emi->persist($account);
            $emi->flush();
        } catch (\Exception $exception) {
            return $this->json(['message' => $exception->getMessage()], 500);
        }

        return $this->json(['message' => 'Account created successfully'], JsonResponse::HTTP_CREATED);
    }

    #[Route('/account/{id}', name: 'get_account', methods: ['GET'])]
    public function getAccount(Account $account): JsonResponse
    {
        $followerList  = $this->getFollowersData($account->getFollowed());
        $followingList = $this->getFollowingData($account->getFollows());

        $accountData = [
            'id'        => $account->getId(),
            'email'     => $account->getEmail(),
            'pseudo'    => $account->getPseudo(),
            'followers' => $followerList,
            'following' => $followingList,
        ];

        if (!$account->isPrivate())
            $accountData['posts'] = $this->getUserPostsData($account->getPosts());

        return $this->json($accountData);
    }

    private function getFollowersData($followers): array
    {
        $followerList = [];
        foreach ($followers as $follower) {
            $followerList[] = [
                'id'     => $follower->getId(),
                'email'  => $follower->getEmail(),
                'pseudo' => $follower->getPseudo(),
            ];
        }

        return $followerList;
    }

    private function getFollowingData($following): array
    {
        $followingList = [];

        foreach ($following as $followedUser) {
            $followingList[] = [
                'id'     => $followedUser->getId(),
                'email'  => $followedUser->getEmail(),
                'pseudo' => $followedUser->getPseudo(),
            ];
        }

        return $followingList;
    }

    private function getUserPostsData($posts): array
    {
        $postsData = [];

        foreach ($posts as $post) {
            $postsData = [
                'id'          => $post->getId(),
                'description' => $post->getDescription(),
                'media'       => $post->getMedia()->getPicture()
            ];
        }

        return $postsData;
    }

    #[Route('/account/update/{id}', name: 'update_account', methods: ['PATCH'])]
    public function updateAccount(Account $account, Request $request, EntityManagerInterface $emi): JsonResponse
    {
        $data = $request->query->all();

        $account->setEmail($data['email'] ?? $account->getEmail());
        $account->setRoles($data['roles'] ?? [$account->getRoles()]);
        $account->setPseudo($data['pseudo'] ?? $account->getPseudo());
        $account->setDescription($data['description'] ?? $account->getDescription());

        if (isset($data['private'])) {
            if (filter_var($data['private'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) === null)
                return $this->json(['message' => "Wrong value for validate update"], 400);
            else
                $account->setPrivate(filter_var($data['private'], FILTER_VALIDATE_BOOLEAN) ?? $account->isPrivate());
        }

        try {
            $emi->persist($account);
            $emi->flush();
        } catch (\Exception $exception) {
            return $this->json(['message' => $exception->getMessage()], 500);
        }

        return $this->json(['message' => 'Account updated successfully']);
    }

    #[Route('/account/delete/{id}', name: 'delete_account', methods: ['DELETE'])]
    public function deleteAccount(Account $account, EntityManagerInterface $emi): JsonResponse
    {
        $emi->remove($account);
        $emi->flush();

        return $this->json(['message' => 'Account deleted successfully']);
    }

    // todo: add function to modify mdp
}
