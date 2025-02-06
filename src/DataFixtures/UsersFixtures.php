<?php

namespace App\DataFixtures;

use App\Entity\Users;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UsersFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }
    public function load(ObjectManager $manager): void
    {
        $admin = new Users();
        $admin->setFirstName("Adam")
            ->setLastName("Adminovich")
            ->setEmail("admin@email.com")
            ->setRole(["role_admin"]);
        $hashedPassword = $this->passwordHasher ->hashPassword($admin,"admin_password");
        $admin->setPassword($hashedPassword);
        $manager->persist($admin);

        
        $client = new Users();
        $client->setFirstName("Client")
            ->setLastName("Clientovich")
            ->setEmail("client1@email.com")
            ->setRole(['role_client']);
        $hashedPassword = $this->passwordHasher->hashPassword($client, 'client_password');
        $client->setPassword($hashedPassword);

        $manager->persist($client);

        $this->addReference("client1",$client);
        
        $worker = new Users();
        $worker->setFirstName("Worker")
            ->setLastName("Workerovich")
            ->setEmail("worker1@email.com")
            ->setRole(["role_worker"]);

        $hashedPassword = $this->passwordHasher->hashPassword($worker, "worker_password");
        $worker->setPassword($hashedPassword);

        $manager->persist($worker);

        $this->addReference("worker1",$worker);
        
        $manager->flush();
    }
}
