<?php

namespace App\Repository;

use App\Entity\ImportFile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ImportFile|null find($id, $lockMode = null, $lockVersion = null)
 * @method ImportFile|null findOneBy(array $criteria, array $orderBy = null)
 * @method ImportFile[]    findAll()
 * @method ImportFile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImportFileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ImportFile::class);
    }

    // /**
    //  * @return ImportFile[] Returns an array of ImportFile objects
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
    public function findOneBySomeField($value): ?ImportFile
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
