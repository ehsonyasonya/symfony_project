<?php

namespace App\DataFixtures;

use App\Entity\Schedule;
use App\Entity\Users;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entity\Employees;

class ScheduleFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $employee = $this->getReference("employee_worker1", Employees::class);

        $schedule = new Schedule();
        $schedule->setDate(new \DateTime("2025-02-04"))
            ->setStartTime(new \DateTime("11:00"))
            ->setEndTime(new \DateTime("11:45"))
            ->setEmployeeId($employee);

        $manager->persist($schedule);
        $manager->flush();

        $this->addReference("schedule_worker1", $schedule);
    }

    public function getDependencies(): array
    {
        return [
            EmployeesFixtures::class,  
        ];
    }
}
