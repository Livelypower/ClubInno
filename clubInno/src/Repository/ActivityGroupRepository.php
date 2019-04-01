<?php

namespace App\Repository;

use App\Entity\ActivityGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ActivityGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method ActivityGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method ActivityGroup[]    findAll()
 * @method ActivityGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActivityGroupRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ActivityGroup::class);
    }

    // /**
    //  * @return ActivityGroup[] Returns an array of ActivityGroup objects
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
    public function findOneBySomeField($value): ?ActivityGroup
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
