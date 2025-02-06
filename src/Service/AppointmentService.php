<?php

namespace App\Service;

use App\Repository\AppointmentsRepository;
use App\Repository\EmployeesRepository;
use App\Repository\UsersRepository;
use App\Repository\ServicesRepository;
use App\Entity\Appointments;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;
use LogicException;

class AppointmentService
{
    private $appointmentRepository;
    private $employeeRepository;
    private $userRepository;
    private $serviceRepository;
    private $entityManager;

    public function __construct(
        AppointmentsRepository $appointmentRepository,
        EmployeesRepository $employeeRepository,
        UsersRepository $userRepository,
        ServicesRepository $serviceRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->appointmentRepository = $appointmentRepository;
        $this->employeeRepository = $employeeRepository;
        $this->userRepository = $userRepository;
        $this->serviceRepository = $serviceRepository;
        $this->entityManager = $entityManager;
    }

    public function createAppointment($data)
    {
        $employee = $this->employeeRepository->find($data['employee_id']);
        $user = $this->userRepository->find($data['client_id']);
        $service = $this->serviceRepository->find($data['service_id']);

        if (!$employee || !$user || !$service) {
            throw new InvalidArgumentException("Invalid client, employee, or service.");
        }

        $appointmentDate = \DateTime::createFromFormat('Y-m-d', $data['appointment_date']);
        $appointmentTime = \DateTime::createFromFormat('H:i:s', $data['appointment_time']);

        $existingAppointment = $this->appointmentRepository->findByEmployeeAndTime(
            $employee->getId(),
            $appointmentDate,
            $appointmentTime
        );

        if ($existingAppointment) {
            throw new LogicException("Employee is already booked for this time.");
        }

        $appointment = new Appointments();
        $appointment->setClientId($user);
        $appointment->setEmployeeId($employee);
        $appointment->setServiceId($service);
        $appointment->setAppointmentDate(new \DateTime($data['appointment_date']));
        $appointment->setAppointmentTime(new \DateTime($data['appointment_time']));
        $appointment->setStatus('pending');

        $this->entityManager->persist($appointment);
        $this->entityManager->flush();

        return $appointment;
    }
}
