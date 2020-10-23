<?php

namespace App\Repository;

use App\Entity\EventVenue;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EventVenue|null find($id, $lockMode = null, $lockVersion = null)
 * @method EventVenue|null findOneBy(array $criteria, array $orderBy = null)
 * @method EventVenue[]    findAll()
 * @method EventVenue[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventVenueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EventVenue::class);
    }

    // /**
    //  * @return EventVenue[] Returns an array of EventVenue objects
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
    public function findOneBySomeField($value): ?EventVenue
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
