<?php

namespace App\Repository;

use App\Entity\Aide;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Aide|null find($id, $lockMode = null, $lockVersion = null)
 * @method Aide|null findOneBy(array $criteria, array $orderBy = null)
 * @method Aide[]    findAll()
 * @method Aide[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AideRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Aide::class);
    }
    /**
     * @param $nom
     * @return mixed
     */
    public function findByNom($nom){
        return $this->createQueryBuilder('p')
            ->select('p.id,p.sujet')
            ->andWhere('p.sujet like :val')
            ->setParameter('val','%'.$nom.'%')
            ->orderBy('p.id' , 'ASC')
            ->getQuery()
            ->getResult();

    }

    // /**
    //  * @return Aide[] Returns an array of Aide objects
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
    public function findOneBySomeField($value): ?Aide
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
