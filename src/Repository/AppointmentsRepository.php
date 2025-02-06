<?php

namespace App\Repository;

use App\Entity\Appointments;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Appointments>
 */
class AppointmentsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Appointments::class);
    }

    public function findByEmployeeAndTime(int $employeeId, \DateTime $appointmentDate, \DateTime $appointmentTime)
    {
        return $this->createQueryBuilder('a')
            ->where('a.employee = :employeeId')
            ->andWhere('a.appointmentDate = :appointmentDate')
            ->andWhere('a.appointmentTime = :appointmentTime')
            ->setParameter('employeeId',$employeeId)
            ->setParameter('appointmentDate',$appointmentDate->format('Y-m-d'))
            ->setParameter('appointmentTime',$appointmentTime->format('H:i:s'))
            ->getQuery()
            ->getOneOrNullResult();
    }
}
