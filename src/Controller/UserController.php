<?php

namespace App\Controller;

use App\Entity\Users;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

class UserController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private JWTTokenManagerInterface $jwtManager;

    public function __construct(EntityManagerInterface $entityManager, JWTTokenManagerInterface $jwtManager)
    {
        $this->entityManager = $entityManager;
        $this->jwtManager = $jwtManager;
    }

    #[Route('/users', name: 'get_users', methods: ['GET'])]
    public function list(SerializerInterface $serializer): JsonResponse
    {
        $users = $this->entityManager->getRepository(Users::class)->findAll();

        $jsonData = $serializer->serialize($users, 'json', ['groups' => 'user:read']);


        return new JsonResponse($jsonData, 200, [], true);
    }

    #[Route('/user/{id}', name: 'get_user', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function show(UsersRepository $usersRepository, int $id, SerializerInterface $serializer): JsonResponse
    {
        $user = $usersRepository->find($id);
        if (!$user) {
            return $this->json(['message' => 'User not found'], 404);
        }

        $data = $serializer->serialize($user, 'json', [
            AbstractNormalizer::ATTRIBUTES => ['id', 'firstName', 'lastName', 'email', 'roles'], 
            AbstractNormalizer::IGNORED_ATTRIBUTES => ['password', 'salt'] 
        ]);
        return new JsonResponse($data, 200, [], true);
    }

    #[Route('/user/create', name: 'create_user', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!$data || !isset($data['firstName'], $data['lastName'], $data['email'], $data['role'], $data['password'])) {
            return $this->json(['error' => 'Missing required fields'], 400);
        }

        $user = new Users();
        $user->setFirstName($data['firstName'])
            ->setLastName($data['lastName'])
            ->setEmail($data['email'])
            ->setRole([$data['role']]); 

        $hashedPassword = $passwordHasher->hashPassword($user, $data['password']);
        $user->setPassword($hashedPassword);

        $entityManager->persist($user);
        $entityManager->flush();

        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            $token = $this->jwtManager->create($user);

            return $this->json([
                'message' => 'User created successfully',
                'token' => $token,
            ], 201);
        }

        return $this->json(['message' => 'User created successfully'], 201);
    }

    #[Route('/user/{id}/update', name: 'update_user', methods: ['PUT'], requirements: ['id' => '\d+'])]
    public function update(Request $request, UsersRepository $usersRepository, EntityManagerInterface $entityManager, int $id): JsonResponse
    {
        $user = $usersRepository->find($id);
        if (!$user) {
            return $this->json(['message' => 'User not found'], 404);
        }

        $data = json_decode($request->getContent(), true);
        if (isset($data['firstName'])) {
            $user->setFirstName($data['firstName']);
        }
        if (isset($data['lastName'])) {
            $user->setLastName($data['lastName']);
        }
        if (isset($data['email'])) {
            $user->setEmail($data['email']);
        }

        $entityManager->flush();

        return $this->json(['message' => 'User updated successfully']);
    }

    #[Route('/user/{id}/delete', name: 'delete_user', methods: ['DELETE'], requirements: ['id' => '\d+'])]
    public function delete(UsersRepository $usersRepository, EntityManagerInterface $entityManager, int $id): JsonResponse
    {
        $user = $usersRepository->find($id);
        if (!$user) {
            return $this->json(['message' => 'User not found'], 404);
        }

        $entityManager->remove($user);
        $entityManager->flush();

        return $this->json(['message' => 'User deleted successfully']);
    }
}
