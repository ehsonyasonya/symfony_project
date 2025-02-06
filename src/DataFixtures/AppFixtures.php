<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Users;
use App\Entity\Appointments;
use App\Entity\Services;
use App\Entity\Employees;
use App\Entity\Schedule;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $client = new Users();
        $client->setFirstName("John")
            ->setLastName("Doe")
            ->setEmail("client@email.com")
            ->setRole(["role_client"]);
        $hashedPassword = $this->passwordHasher ->hashPassword($client,"client_password");
        $client->setPassword($hashedPassword);
        $manager->persist($client);

        $worker = new Users();
        $worker->setFirstName("Alice")
            ->setLastName("Smith")
            ->setEmail("worker@email.com")
            ->setRole(["role_worker"]);
        $hashedPassword = $this->passwordHasher ->hashPassword($worker,"worker_password");
        $worker->setPassword($hashedPassword);
        $manager->persist($worker);

        $service = new Services();
        $service->setName("consultation")
            ->setDescription("genral consultation service")
            ->setDurationMinutes("45");
        $manager->persist($service);

        $manager->flush();

        $employee = new Employees();
        $employee->setBio("experienced consultant with 2 years in the industry")
            ->setRating("4.5")
            ->setUserId($worker);
        $manager->persist($employee);

        $manager->flush();

        $schedule = new Schedule();
        $schedule->setDate(new \DateTime("2025-01-29"))
            ->setStartTime(new \DateTime("9:00"))
            ->setEndTime(new \DateTime("9:45"))
            ->setEmployeeId($employee);
        $manager->persist($schedule);

        $manager->flush();

        $appointment = new Appointments();
        $appointment->setAppointmentDate(new \DateTime("2025-01-29"))
            ->setAppointmentTime(new \DateTime("9:00"))
            ->setStatus("completed")
            ->setClientId($client)
            ->setEmployeeId($employee)
            ->setServiceId($service);
        $manager->persist($appointment);

        $manager->flush();
    }
}
