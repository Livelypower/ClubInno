<?php

namespace App\Repository;

use App\Entity\ActivityMoment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ActivityMoment|null find($id, $lockMode = null, $lockVersion = null)
 * @method ActivityMoment|null findOneBy(array $criteria, array $orderBy = null)
 * @method ActivityMoment[]    findAll()
 * @method ActivityMoment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActivityMomentRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ActivityMoment::class);
    }

    // /**
    //  * @return ActivityMoment[] Returns an array of ActivityMoment objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ActivityMoment
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
