<?php

namespace App\Command;

use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


#[AsCommand(
    name: 'app:add-user',
    description: 'Adds a new user to the database',
)]
class AddUserCommand extends Command
{
    private EntityManagerInterface $entityManager;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(EntityManagerInterface $entityManager,UserPasswordHasherInterface $passwordHasher)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
    }

    protected function configure()
    {
        $this->setDescription('Adds a new user to the database.')
            ->addArgument('first_name', InputArgument::REQUIRED, 'First name of the user')
            ->addArgument('last_name', InputArgument::REQUIRED, 'Last name of the user')
            ->addArgument('email', InputArgument::REQUIRED, 'Email of the user')
            ->addArgument('role', InputArgument::REQUIRED, 'Role of the user (role_client, role_worker, role_admin)')
            ->addArgument('password', InputArgument::REQUIRED, 'The password of the user')
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $firstName = $input->getArgument('first_name');
        $lastName = $input->getArgument('last_name');
        $email = $input->getArgument('email');
        $role = $input->getArgument('role');
        $plainPassword = $input->getArgument('password');

        $user = new Users();
        $encodedPassword = $this->passwordHasher->hashPassword($user, $plainPassword);
        $user->setFirstName($firstName)
            ->setLastName($lastName)
            ->setEmail($email)
            ->setRole([$role])
            ->setPassword($encodedPassword);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $output->writeln('User created successfully!');

        return Command::SUCCESS;
    }
}
