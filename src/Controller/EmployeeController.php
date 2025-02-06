<?php

namespace App\Controller;

use App\Entity\Employees;
use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

final class EmployeeController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    #[Route('/employees', name: 'get_employees', methods: ['GET'])]
    public function getAllEmployees(SerializerInterface $serializer): JsonResponse
    {
        $employees = $this->entityManager->getRepository(Employees::class)->findAll();

        $jsonData = $serializer->serialize($employees, 'json', ['groups' => 'employee:read']);

        return new JsonResponse($jsonData, 200, [], true);
    }

    #[Route('/employee/{id}', name: 'get_employee', methods: ['GET'])]
    public function getEmployee($id, SerializerInterface $serializer): JsonResponse
    {
        $employee = $this->entityManager->getRepository(Employees::class)->find($id);

        if (!$employee) {
            return $this->json(['error' => 'Employee not found'], 404);
        }

        $jsonData = $serializer->serialize($employee, 'json', ['groups' => 'employee:read']);

        return new JsonResponse($jsonData, 200, [], true);
    }

    #[Route('/employee/create', name: 'create_employee', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager,SerializerInterface $serializer): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!$data || !isset($data['user_id'], $data['bio'], $data['rating'])) {
            return $this->json(['error' => 'Missing required fields'], 400);
        }

        $user = $entityManager->getRepository(Users::class)->find($data['user_id']);
        if (!$user) {
            return $this->json(['error' => 'User not found'], 404);
        }

        $employee = new Employees();
        $employee->setUserId($user)
                 ->setBio($data['bio'])
                 ->setRating($data['rating']);
        
        $entityManager->persist($employee);
        $entityManager->flush();

        $jsonEmployee = $serializer->serialize($employee, 'json', ['groups' => ['employee:read']]);

        return new JsonResponse($jsonEmployee, 201, [], true);
    }
}
