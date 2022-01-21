<?php

namespace App\Repository;

use App\Entity\UserSubject;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserSubject|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserSubject|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserSubject[]    findAll()
 * @method UserSubject[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserSubjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserSubject::class);
    }

    // /**
    //  * @return UserSubject[] Returns an array of UserSubject objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UserSubject
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
