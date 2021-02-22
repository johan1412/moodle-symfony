<?php

namespace App\Repository;

use App\Entity\CorrectAnswer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CorrectAnswer|null find($id, $lockMode = null, $lockVersion = null)
 * @method CorrectAnswer|null findOneBy(array $criteria, array $orderBy = null)
 * @method CorrectAnswer[]    findAll()
 * @method CorrectAnswer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CorrectAnswerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CorrectAnswer::class);
    }

    // /**
    //  * @return CorrectAnswer[] Returns an array of CorrectAnswer objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CorrectAnswer
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
