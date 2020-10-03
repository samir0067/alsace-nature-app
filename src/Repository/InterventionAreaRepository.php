<?php

namespace App\Repository;

use App\Entity\InterventionArea;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method InterventionArea|null find($id, $lockMode = null, $lockVersion = null)
 * @method InterventionArea|null findOneBy(array $criteria, array $orderBy = null)
 * @method InterventionArea[]    findAll()
 * @method InterventionArea[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InterventionAreaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InterventionArea::class);
    }

    // /**
    //  * @return InterventionArea[] Returns an array of InterventionArea objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?InterventionArea
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
