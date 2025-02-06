<?php

namespace App\DataFixtures;

use App\Entity\Employees;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entity\Users;

class EmployeesFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $worker = $this->getReference("worker1",Users::class);

        $employee = new Employees();
        $employee->setUserId($worker)
            ->setBio("new staff member")
            ->setRating("4.5");

        $manager->persist($employee);

        $this->addReference("employee_worker1", $employee);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UsersFixtures::class,
        ];
    }
}
