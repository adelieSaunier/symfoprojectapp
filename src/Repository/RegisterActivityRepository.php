<?php

namespace App\Repository;

use App\Entity\RegisterActivity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RegisterActivity|null find($id, $lockMode = null, $lockVersion = null)
 * @method RegisterActivity|null findOneBy(array $criteria, array $orderBy = null)
 * @method RegisterActivity[]    findAll()
 * @method RegisterActivity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RegisterActivityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RegisterActivity::class);
    }

    // /**
    //  * @return RegisterActivity[] Returns an array of RegisterActivity objects
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
    public function findOneBySomeField($value): ?RegisterActivity
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
