<?php

namespace App\Entity;

use App\Repository\AppointmentsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity(repositoryClass=AppointmentRepository::class)
 * @ORM\Table(
 *     name="appointments",
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(name="unique_employee_date_time", columns={"employee_id", "date", "start_time", "end_time"})
 *     }
 * )
 */
#[ORM\Entity(repositoryClass: AppointmentsRepository::class)]
class Appointments
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(targetEntity: Users::class)]
    #[ORM\JoinColumn(name: "client_id", referencedColumnName: "id")]
    private ?Users $client_id = null;

    #[ORM\OneToOne(targetEntity: Employees::class)]
    #[ORM\JoinColumn(name: "employee_id", referencedColumnName: "id")]
    private ?Employees $employee_id = null;

    #[ORM\OneToOne(targetEntity: Services::class)]
    #[ORM\JoinColumn(name: "service_id", referencedColumnName: "id")]
    private ?Services $service_id = null;



    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $appointment_date = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $appointment_time = null;

    #[ORM\Column(length: 20)]
    private ?string $status = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClientId(): ?Users
    {
        return $this->client_id;
    }

    public function setClientId(?Users $client_id): static
    {
        $this->client_id  = $client_id;
        return $this;
    }

    public function getEmployeeId(): ?Employees
    {
        return $this->employee_id;
    }

    public function setEmployeeId(?Employees $employee_id): static
    {
        $this->employee_id  = $employee_id;
        return $this;
    }

    public function getServiceId(): ?Services
    {
        return $this->service_id;
    }

    public function setServiceId(?Services $service_id): static
    {
        $this->service_id  = $service_id;
        return $this;
    }

    public function getAppointmentDate(): ?\DateTimeInterface
    {
        return $this->appointment_date;
    }

    public function setAppointmentDate(\DateTimeInterface $appointment_date): static
    {
        $this->appointment_date = $appointment_date;

        return $this;
    }

    public function getAppointmentTime(): ?\DateTimeInterface
    {
        return $this->appointment_time;
    }

    public function setAppointmentTime(\DateTimeInterface $appointment_time): static
    {
        $this->appointment_time = $appointment_time;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }
}
