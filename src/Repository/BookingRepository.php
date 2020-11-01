<?php

namespace App\Repository;

use App\Entity\Booking;
use App\Entity\Event;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Booking|null find($id, $lockMode = null, $lockVersion = null)
 * @method Booking|null findOneBy(array $criteria, array $orderBy = null)
 * @method Booking[]    findAll()
 * @method Booking[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Booking::class);
    }

    public function isUserBooked(User $user): bool
    {
        $qb = $this->createQueryBuilder('b')
            ->where('b.user = :user')
            ->setParameter('user', $user);

        return $query = $qb->getQuery()->getOneOrNullResult() ? true : false;
    }

    public function getUserBooking(User $user, Event $event)
    {
        $qb = $this->createQueryBuilder('b')
            ->where('b.user = :user')
            ->andWhere('b.event = :event')
            ->setParameter('user', $user)
            ->setParameter('event', $event);

        return $query = $qb->getQuery()->getOneOrNullResult();
    }

    // /**
    //  * @return Booking[] Returns an array of Booking objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Booking
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
