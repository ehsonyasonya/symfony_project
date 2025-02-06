<?php

namespace App\Controller;

use App\Entity\Schedule;
use App\Entity\Employees;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class ScheduleController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/schedules', name: 'get_all_schedules', methods: ['GET'])]
    public function getAllSchedules(SerializerInterface $serializer): JsonResponse
    {
        $schedules = $this->entityManager->getRepository(Schedule::class)->findAll();

        $jsonData = $serializer->serialize($schedules, 'json', ['groups' => 'schedule:read']);

        return new JsonResponse($jsonData, 200, [], true);
    }
    
    #[Route('/schedule/{employeeId}', name: 'get_schedule', methods:['GET'])]
    public function index($employeeId, SerializerInterface $serializer): JsonResponse
    {
        $employee = $this->entityManager->getRepository(Employees::class)->find($employeeId);

        if (!$employee) {
            return $this->json(['error' => 'Employee not found'], 404);
        }

        $schedule = $this->entityManager->getRepository(Schedule::class)->findBy(['employee_id' => $employee]);

        if (!$schedule) {
            return $this->json(['error' => 'Schedule not found for this employee'], 404);
        }

        $jsonData = $serializer->serialize($schedule, 'json', ['groups' => 'schedule:read']);

        return new JsonResponse($jsonData, 200, [], true);
    }
}
