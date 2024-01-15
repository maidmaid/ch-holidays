<?php

namespace App\Repository;

use App\Entity\HolidayType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<HolidayType>
 *
 * @method HolidayType|null find($id, $lockMode = null, $lockVersion = null)
 * @method HolidayType|null findOneBy(array $criteria, array $orderBy = null)
 * @method HolidayType[]    findAll()
 * @method HolidayType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HolidayTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HolidayType::class);
    }
}
