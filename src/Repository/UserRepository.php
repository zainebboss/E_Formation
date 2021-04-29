<?php

namespace App\Repository;


use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function findAllIndexed()
    {
        $qb = $this->createQueryBuilder('foo');
        $query = $qb
            ->indexBy('foo', 'foo.email')
            ->getQuery();
        return $query->getResult();
    }

    public function findUserByEmail($email)
    {

        $query = $this->createQueryBuilder('u')
            ->where('u.email = :email ')
            ->andWhere('u.enable = true')
            ->setParameter('email', $email)
            ->getQuery();
        return $query->getOneOrNullResult();

    }
    public function findUsersByRole($search=null,$role=null)
    {

        $query = $this->createQueryBuilder('u')
            ->where('u.roles LIKE :role')
              ->setParameter('role', '%'.$role.'%');
              if($search!= ''){
                  $query ->andWhere('u.email like :search or u.nom like :search or u.prenom like :search 
                  or u.telephone like :search or u.adresse like :search ')
                      ->setParameter('search', '%'.$search.'%');
              }
        return $query->getQuery()->getResult();

    }
}
