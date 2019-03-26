<?php

namespace App\Repository;

use App\Entity\ReBoard;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ReBoard|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReBoard|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReBoard[]    findAll()
 * @method ReBoard[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReBoardRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ReBoard::class);
    }

    // /**
    //  * @return ReBoard[] Returns an array of ReBoard objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ReBoard
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
