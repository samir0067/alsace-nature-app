<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Evenement;
use App\Entity\Structure;
use App\Entity\Theme;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\This;

/**
 * @method Evenement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Evenement|null findOneBy(array $criteria, array $orderBy = null)
// * @method Evenement[]    findAll()
 * @method Evenement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EvenementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Evenement::class);
    }

    public function findAll()
    {
        $qb = $this->createQueryBuilder('e')
            ->innerJoin('e.category', 'c')
            ->innerJoin('e.theme', 't')
            ->leftJoin('e.report', 'r')
            ->addSelect('c')
            ->addSelect('t')
            ->addSelect('r')
            ->orderBy('e.dateStart', 'DESC')
            ->getQuery();

        return $qb->execute();
    }

    public function findAllEvents($date)
    {
        $qb = $this->createQueryBuilder('e')
            ->innerJoin('e.category', 'c')
            ->innerJoin('e.theme', 't')
            ->leftJoin('e.report', 'r')
            ->addSelect('c')
            ->addSelect('t')
            ->addSelect('r')
            ->where("e.dateStart >= '$date'")
            ->orderBy('e.dateStart', 'DESC')
            ->getQuery();

        return $qb->execute();
    }


    public function findEvents($date)
    {
        $query = $this->createQueryBuilder('e')
            ->andWhere("e.dateStart >= '$date'")
            ->orderBy('e.dateStart', 'ASC')
            ->setMaxResults(30)
            ->getQuery()
            ->getResult();
        return $query;
    }

    public function countAll() :int
    {
        $result = count($this->findAll());
        return $result;
    }

    public function filter($cat, $theme, $structure, $dateStart, $dateStop, $options)
    {
        $query = $this->createQueryBuilder('e')
                ->join('e.category', 'c')
                ->join('e.theme', 't')
                ->join('e.structure', 's');

        $query->where("e.dateStart >= '$dateStart'");
        if ($cat != 'all') {
            $query->andWhere("c.name = '$cat'");
        }
        if ($theme != 'all') {
            $query->andWhere("t.name = '$theme'");
        }
        if ($structure != null) {
                $query->andWhere("s.completeName = '$structure'");
        }

        if ($dateStop != null) {
            $query->andWhere("e.dateStop <= '$dateStop'");
        }
        if (!empty($options)) {
            foreach ($options as $option) {
                switch ($option) {
                    case "free":
                        $query->andWhere("e.cost = 0");
                        break;
                    case "register":
                        $query->andWhere("e.registerRequired = 0");
                        break;
                }
            }
        }

        return $query->orderBy('e.dateStart', 'ASC')
                ->setMaxResults(20)
                ->getQuery()
                ->getResult();
    }
}
