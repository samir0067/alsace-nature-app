<?php

namespace App\Repository;

use App\Entity\ValidationAuthorization;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ValidationAuthorization|null find($id, $lockMode = null, $lockVersion = null)
 * @method ValidationAuthorization|null findOneBy(array $criteria, array $orderBy = null)
 * @method ValidationAuthorization[]    findAll()
 * @method ValidationAuthorization[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ValidationAuthorizationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ValidationAuthorization::class);
    }

    // /**
    //  * @return ValidationAuthorization[] Returns an array of ValidationAuthorization objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ValidationAuthorization
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
