<?php

namespace App\Command;

use App\Entity\Services;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:add-service',
    description: 'Add a new service in the system.',
)]
class AddServiceCommand extends Command
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function configure()
    {
        $this->addArgument('name', InputArgument::REQUIRED, 'The name of the service')
            ->addArgument('duration', InputArgument::REQUIRED, 'The duration of the service in minutes')
            ->addArgument('description', InputArgument::OPTIONAL, 'A description of the service')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $name = $input->getArgument('name');
        $description = $input->getArgument('description');
        $duration = (int) $input->getArgument('duration');

        $service = new Services();
        $service->setName($name)
            ->setDurationMinutes($duration)
            ->setDescription($description);

        $this->entityManager->persist($service);
        $this->entityManager->flush();

        $output->writeln('Service created successfully!');

        return Command::SUCCESS;
    }
}
