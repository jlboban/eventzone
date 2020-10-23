<?php

namespace App\Repository;

use App\Entity\EventMusician;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EventMusician|null find($id, $lockMode = null, $lockVersion = null)
 * @method EventMusician|null findOneBy(array $criteria, array $orderBy = null)
 * @method EventMusician[]    findAll()
 * @method EventMusician[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventMusicianRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EventMusician::class);
    }

    // /**
    //  * @return EventMusician[] Returns an array of EventMusician objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EventMusician
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
