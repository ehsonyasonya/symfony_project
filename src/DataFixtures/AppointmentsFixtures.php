<?php

namespace App\DataFixtures;

use App\Entity\Appointments;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entity\Users;
use App\Entity\Employees;
use App\Entity\Services;
use App\Entity\Schedule;

class AppointmentsFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $client = $this->getReference("client1",Users::class);
        $employee = $this->getReference("employee_worker1",Employees::class);
        $service = $this->getReference("service_consultation",Services::class);
        $schedule = $this->getReference("schedule_worker1",Schedule::class);

        $appointment = new Appointments();
        $appointment->setAppointmentDate(new \DateTime("2025-02-04"))
            ->setAppointmentTime(new \DateTime("11:00"))
            ->setStatus("completed")
            ->setClientId($client)
            ->setEmployeeId($employee)
            ->setServiceId($service);

        $manager->persist($appointment);
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UsersFixtures::class,
            EmployeesFixtures::class,
            ScheduleFixtures::class,
            ServicesFixtures::class,
        ];
    }
}
