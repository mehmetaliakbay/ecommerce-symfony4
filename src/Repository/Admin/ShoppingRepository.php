<?php

namespace App\Repository\Admin;

use App\Entity\Admin\Shopping;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Shopping|null find($id, $lockMode = null, $lockVersion = null)
 * @method Shopping|null findOneBy(array $criteria, array $orderBy = null)
 * @method Shopping[]    findAll()
 * @method Shopping[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShoppingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Shopping::class);
    }

    // /**
    //  * @return Shopping[] Returns an array of Shopping objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Shopping
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    // *** LEFT JOIN WITH SQL ******
    public function getUserShopping($id): array
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT s.*,p.title as pname FROM shopping s
                JOIN product p  ON p.id = s.productid
                WHERE s.userid = :userid
                ORDER BY s.id DESC';
        $stmt = $conn->prepare($sql);
        $stmt->execute(['userid'=>$id]);

        // return an array of arrays (i.e a raw data set)

        return $stmt->fetchAll();
    }

    
    // *** LEFT JOIN WITH SQL ******
    public function getShopping($id): array
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT s.*,p.title as pname, usr.name as uname FROM shopping s
                JOIN product p  ON p.id = s.productid
                JOIN user usr  ON usr.id = s.userid
                WHERE s.id = :id';
        $stmt = $conn->prepare($sql);
        $stmt->execute(['id'=>$id]);

        // return an array of arrays (i.e a raw data set)

        return $stmt->fetchAll();
    }
        // *** LEFT JOIN WITH SQL ******
        public function getShoppings($status): array
        {
            $conn = $this->getEntityManager()->getConnection();
            $sql = 'SELECT s.*, p.title as pname, usr.name as uname FROM shopping s
                    JOIN product p  ON p.id = s.productid
                    JOIN user usr  ON usr.id = s.userid
                    WHERE s.status =:status
                    ';
            $stmt = $conn->prepare($sql);
            $stmt->execute(['status'=>$status]);
    
            // return an array of arrays (i.e a raw data set)
    
            return $stmt->fetchAll();
        }
}
