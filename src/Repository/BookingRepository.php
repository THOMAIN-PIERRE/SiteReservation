<?php

namespace App\Repository;

use App\Entity\Booking;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;


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


    /**
    * Returns number of "commandes" per day
    * @return void
    */
    public function countByDate()
    {
        $queryBuilder = $this->createQueryBuilder('b')
                             ->select('SUBSTRING(b.createdAt, 1, 10) as dateBooking, COUNT(b.amount) as booked')
                            //  ->select('SUBSTRING(o.createdAt, 1, 10) as dateCommande, current_date() as date, COUNT(o.stripeSessionId) as convertedOrders')
                            //  ->where ('SUBSTRING(o.createdAt, 1, 10) = current_month()')
                            //  ->where ('MONTH(SUBSTRING(o.createdAt, 1, 10)) = current_month(date)')
                             ->groupBy('dateBooking');

        return $queryBuilder->getQuery()->getResult();
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

