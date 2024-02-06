<?php

namespace App\Controller;

use App\Entity\Account;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Serializer\SerializerInterface;

#[OA\Tag(
    name: 'Account',
    description: 'Operations related to account management such as creating, updating, retrieving, and logging in user accounts.'
)]
class AccountController extends AbstractController
{
    private EntityManagerInterface $emi;
    private SerializerInterface    $serializer;

    public function __construct(
        EntityManagerInterface $emi,
        SerializerInterface    $serializer
    )
    {
        $this->emi = $emi;
        $this->serializer = $serializer;
    }
    
    #[OA\Response(
        response: 200,
        description: 'Return a json array with a validation message and the email of the login',
    )]
    #[OA\Parameter(
        name: 'mail',
        in: 'query',
        description: 'User\'s email',
        required: true
    )]
    #[OA\Parameter(
        name: 'password',
        in: 'query',
        description: 'User\'s password',
        required: true
    )]
    #[Route('api/login', name: 'api_login', methods: ['POST'])] // no logout, juste need to wipe cookies with JS in the front
    public function login(#[CurrentUser] ?Account $account): JsonResponse
    {
        if ($account === null)
            return $this->json(['message' => 'missing credentials', Response::HTTP_UNAUTHORIZED]);

        return new JsonResponse(['message' => 'User connected', 'user' => $account->getUserIdentifier()]);
    }

    #[OA\Response(
        response: 200,
        description: 'Return a json array with a validation message',
    )]
    #[OA\Parameter(
        name: 'email',
        in: 'query',
        description: 'User\'s email',
        required: true
    )]
    #[OA\Parameter(
        name: 'pseudo',
        in: 'query',
        description: 'User\'s Account name',
        required: true
    )]
    #[OA\Parameter(
        name: 'password',
        in: 'query',
        description: 'User\'s password',
        required: true
    )]
    #[OA\Parameter(
        name: 'description',
        in: 'query',
        description: 'User\'s profile description',
        required: false
    )]
    #[OA\Parameter(
        name: 'private',
        in: 'query',
        description: 'Determine if a user is private or not, defining if he can be visible by other users',
        required: false
    )]
    #[OA\Parameter(
        name: 'role',
        in: 'query',
        description: 'WIP -- Work In Progress (should be empty first)',
        required: false
    )]
    #[Route('api/account/create', name: 'create_account', methods: ['POST'])]
    public function createUser(Request $request, UserPasswordHasherInterface $passwordEncoder): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $account = new Account();
        $account->setEmail($data['email']);
        $account->setPseudo($data['pseudo']);

        // Hash password
        $hashedPassword = $passwordEncoder->hashPassword($account, $data['password']);
        $account->setPassword($hashedPassword);

        if ($data['description'] !== "")
            $account->setDescription($data['description']);
        if ($data['private'] !== "")
            $account->setPrivate($data['private']);
        $account->setRoles([$data['roles']]);

        try {
            $this->emi->persist($account);
            $this->emi->flush();
        } catch (Exception $exception) {
            return $this->json(['message' => $exception->getMessage()], 500);
        }

        return $this->json(['message' => 'Account created successfully'], JsonResponse::HTTP_CREATED);
    }

    #[OA\Response(
        response: 200,
        description: 'Return a json array with a the id of the user, and the pseudo',
    )]
    #[Route('api/account/get/{id}', name: 'get_account', methods: ['GET'])]
    public function getAccount(Account $account): JsonResponse
    {
        $accountData = [
            'id'        => $account->getId(),
            'pseudo'    => $account->getPseudo(),
        ];

        if (!$account->isPrivate() || $account === $this->getUser()) {
            $accountData['description'] = $account->getDescription();
            $accountData['posts']       = $account->getPosts();
        }

        $accountData = $this->serializer->serialize($accountData, 'json', ['account_data']);

        return $this->json($accountData, 'account_data');
    }

    #[OA\Response(
        response: 200,
        description: 'Return a json array with a validation message if succeed or not',
    )]
    #[OA\Parameter(
        name: 'id',
        in: 'query',
        description: 'User\'s id',
        required: true
    )]
    #[OA\Parameter(
        name: 'email',
        in: 'query',
        description: 'User\'s email',   
        required: true
    )]
    #[OA\Parameter(
        name: 'pseudo',
        in: 'query',
        description: 'User\'s account name',
        required: true
    )]
    #[OA\Parameter(
        name: 'description',
        in: 'query',
        description: 'User\'s profile description',
        required: false
    )]
    #[OA\Parameter(
        name: 'private',
        in: 'query',
        description: 'Determine if a user is private or not, defining if he can be visible by other users',
        required: false
    )]
    #[Route('/api/account/update/{id}', name: 'update_account', methods: ['PUT'])]
    public function updateAccount(Account $account, Request $request): JsonResponse
    {
        $currentUser = $this->getUser();

        if ($currentUser !== $account)
            return $this->json(['message' => 'Request not found'], Response::HTTP_NOT_FOUND); // to protect api route from attack

        $data = json_decode($request->getContent(), true);

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
            $this->emi->persist($account);
            $this->emi->flush();
        } catch (Exception $exception) {
            return $this->json(['message' => $exception->getMessage()], 500);
        }

        return $this->json(['message' => 'Account updated successfully']);
    }

    #[OA\Response(
        response: 200,
        description: 'Return a json array with a validation message if succeed or not',
    )]
    #[Route('/api/account/delete/{id}', name: 'delete_account', methods: ['DELETE'])]
    public function deleteAccount(Account $account, EntityManagerInterface $emi): JsonResponse
    {
        $currentUser = $this->getUser();

        if ($currentUser !== $account)
            return $this->json(['message' => 'Request not found'], Response::HTTP_NOT_FOUND); // to protect api road from attack

        $emi->remove($account);
        $emi->flush();

        return $this->json(['message' => 'Account deleted successfully']);
    }

    // todo: add function to modify mdp
}
