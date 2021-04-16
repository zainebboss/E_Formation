<?php

namespace App\Repository;


use App\Entity\Avis;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Avis|null find($id, $lockMode = null, $lockVersion = null)
 * @method Avis|null findOneBy(array $criteria, array $orderBy = null)
 * @method Avis[]    findAll()
 * @method Avis[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AvisRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Avis::class);
    }





    public function findAvis($note=null)
    {

        $query = $this->createQueryBuilder('a')
            ->addSelect('ap,f')
            ->leftJoin("a.apprenant", "ap")
            ->leftJoin("a.formateur", "f");
          //  ->where('ap is not null and f is not null ');
          //  ->where('u.email = :email ')
           // ->andWhere('u.enabled = true')

          //  ->setParameter('email', $email)
              if($note!= ''){
                  $query ->andWhere('a.note = :note')
                      ->setParameter('note', $note);
              }
        return $query->getQuery()->getResult();

    }
}
