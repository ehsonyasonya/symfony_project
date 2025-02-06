<?php

namespace App\Controller;

use App\Entity\Services;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class ServiceController extends AbstractController
{
    #[Route('/services', name: 'get_services', methods: ['GET'])]
    public function getAllServices(EntityManagerInterface $entityManager): JsonResponse
    {
        $services = $entityManager->getRepository(Services::class)->findAll();

        return $this->json($services);
    }

    #[Route('/service/{id}', name: 'get_service', methods: ['GET'])]
    public function getService($id, EntityManagerInterface $entityManager): JsonResponse
    {
        $service = $entityManager->getRepository(Services::class)->find($id);

        if (!$service) {
            return $this->json(['error' => 'Service not found'], 404);
        }

        return $this->json($service);
    }

    #[Route('/service/create', name: 'create_service', methods: ['POST'])]
    public function createService(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        
        if (!isset($data['name']) || empty($data['name'])) {
            return $this->json(['error' => 'Name is required'], 400);
        }

        $service = new Services();
        $service->setName($data['name'])
            ->setDurationMinutes($data['duration_minutes'])
            ->setDescription($data['description']);
        
        $entityManager->persist($service);
        $entityManager->flush();

        return $this->json(['message' => 'Service created successfully', 'service' => $service], 201);
    }
}
