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

        $user1 = new Users();
        $user1->setFirstName("first_name")
            ->setLastName("last_name")
            ->setEmail("user@email.com")
            ->setRole(["user"]);
        $hashedPassword = $this->passwordHasher ->hashPassword($user1,"user_password");
        $user1->setPassword($hashedPassword);
        $manager->persist($user1);

        $user2 = new Users();
        $user2->setFirstName("first_name")
            ->setLastName("last_name")
            ->setEmail("user@email.com")
            ->setRole(["user"]);
        $hashedPassword = $this->passwordHasher ->hashPassword($user2,"user_password");
        $user2->setPassword($hashedPassword);
        $manager->persist($user2);

        $service = new Services();
        $service->setName("consultation")
            ->setDescription("test")
            ->setDurationMinutes("45");
        $manager->persist($service);

        $manager->flush();

        $employee = new Employees();
        $employee->setBio("test")
            ->setRating("4.5")
            ->setUserId($user1);
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
            ->setClientId($user2)
            ->setEmployeeId($employee)
            ->setServiceId($service);
        $manager->persist($appointment);

        $manager->flush();
    }
}
