<?php

namespace App\Repository;

use App\Entity\Aidecom;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Aidecom|null find($id, $lockMode = null, $lockVersion = null)
 * @method Aidecom|null findOneBy(array $criteria, array $orderBy = null)
 * @method Aidecom[]    findAll()
 * @method Aidecom[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AideComRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Aidecom::class);
    }

    // /**
    //  * @return Aidecom[] Returns an array of Aidecom objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Aidecom
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
