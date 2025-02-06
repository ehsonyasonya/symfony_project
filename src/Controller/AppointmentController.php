<?php

namespace App\Controller;

use App\Entity\Appointments;
use App\Entity\Users;
use App\Entity\Employees;
use App\Entity\Services;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class AppointmentController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/appointments', name: 'get_appointments', methods: ['GET'])]
    public function getAllAppointments(SerializerInterface $serializer): JsonResponse
    {
        $appointments = $this->entityManager->getRepository(Appointments::class)->findAll();

        $jsonData = $serializer->serialize($appointments, 'json', ['groups' => 'appointment:read']);

        return new JsonResponse($jsonData, 200, [], true);
    }

    #[Route('/appointment/{id}', name: 'get_appointment', methods: ['GET'])]
    public function getAppointment($id, SerializerInterface $serializer): JsonResponse
    {
        $appointment = $this->entityManager->getRepository(Appointments::class)->find($id);

        if (!$appointment) {
            return $this->json(['error' => 'Appointment not found'], 404);
        }

        $jsonData = $serializer->serialize($appointment, 'json', ['groups' => 'appointment:read']);

        return new JsonResponse($jsonData, 200, [], true);
    }

    #[Route('/api/appointment/create', name: 'create_appointment', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function createAppointment(Request $request, EntityManagerInterface $entityManager,SerializerInterface $serializer): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['client_id'], $data['employee_id'], $data['service_id'], $data['appointment_date'], $data['appointment_time'], $data['status'])) {
            return $this->json(['error' => 'client_id, employee_id, service_id, appointment_date, appointment_time, and status are required'], 400);
        }

        $client = $entityManager->getRepository(Users::class)->find((int)$data['client_id']);
        $employee = $entityManager->getRepository(Employees::class)->find((int)$data['employee_id']);
        $service = $entityManager->getRepository(Services::class)->find((int)$data['service_id']);

        if (!$client) {
            return $this->json(['error' => 'Client not found'], 404);
        }
        if (!$employee) {
            return $this->json(['error' => 'Employee not found'], 404);
        }
        if (!$service) {
            return $this->json(['error' => 'Service not found'], 404);
        }

        $appointment = new Appointments();
        $appointment->setClientId($client)
            ->setEmployeeId($employee)
            ->setServiceId($service)
            ->setAppointmentDate(new \DateTime($data['appointment_date']))
            ->setAppointmentTime(new \DateTime($data['appointment_time']))
            ->setStatus($data['status']);

        $entityManager->persist($appointment);
        $entityManager->flush();
    
        $jsonAppointment = $serializer->serialize($appointment, 'json', ['groups' => ['appointment:read']]);

        return new JsonResponse($jsonAppointment, 201, [], true);
        
    }
}
