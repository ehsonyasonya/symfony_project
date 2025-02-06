<?php

namespace App\DataFixtures;

use App\Entity\Services;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ServicesFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $service = new Services();
        $service->setName("meeting")
            ->setDescription("quick introduction")
            ->setDurationMinutes(45);

        $manager->persist($service);
        $manager->flush();

        $this->addReference("service_consultation", $service);
    }
}
