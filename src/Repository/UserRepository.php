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
            //->leftJoin("u.station", "s")
        //    ->where('u.roles LIKE :ROLE_CONTROLLER')
            ->where('u.email = :email ')
            ->andWhere('u.enable = true')
          //  ->setParameter('ROLE_CONTROLLER', '%ROLE_CONTROLLER%')
            ->setParameter('email', $email)
            ->getQuery();
        return $query->getOneOrNullResult();

    }
    public function findUsersByRole($search=null,$role=null)
    {

        $query = $this->createQueryBuilder('u')
            //->leftJoin("u.station", "s")
            ->where('u.roles LIKE :role')
          //  ->where('u.email = :email ')
           // ->andWhere('u.enabled = true')
              ->setParameter('role', '%'.$role.'%');
          //  ->setParameter('email', $email)
              if($search!= ''){
                  $query ->andWhere('u.email like :search or u.nom like :search or u.prenom like :search 
                  or u.telephone like :search or u.adresse like :search ')
                      ->setParameter('search', '%'.$search.'%');
              }
        return $query->getQuery()->getResult();

    }
    // ROLE_AUDITEUR_FILIALE: ROLE_AUDITEUR_FILIALE
    //        ROLE_AUDITEUR_BUSINESS_LINE: ROLE_AUDITEUR_BUSINESS_LINE

    public function getListAdmins($array)
    {
        if(array_key_exists("keyWord" ,  $array ))$keyword = $array['keyWord'];
        else $keyword="";

        if(array_key_exists("branch" ,  $array ))$branch = $array['branch'];
        else $branch="";
        if(array_key_exists("acces" ,  $array )) $acces = $array['acces'];
        else $acces="";
        if(array_key_exists("user" ,  $array ))$user = $array['user'];
        else $user="";

        $zone = "";
        if(array_key_exists("zone" ,  $array ))$zone = $array['zone'];


        $result = $this->createQueryBuilder('u')
            ->leftJoin('u.branch','b')
            ->leftJoin('u.acces','a')
            ->where('u.roles LIKE :roles and u.isDeleted = false ')
            ->setParameter('roles', '%ROLE_GESTIONNAIRE%')
        ;
        if ($user != "") {
            $result->andWhere('u.id != :user')
                ->setParameter('user',$user->getId());
        }
        if ($keyword != "") {
            $result->andWhere('u.email LIKE :keyword')
                ->setParameter('keyword', "%" . $keyword . "%");
        }
        if ($branch != "") {
            $result->andWhere('b.id = :branch ')
                ->setParameter('branch',  $branch );
        }
        if ($acces != "") {
            $result->andWhere('a.id = :acces ')
                ->setParameter('acces',  $acces );
        }
        if ($user->getZone()) {
            $result->leftJoin('u.zone','z')
                ->andWhere('z.id = :zone ')
                ->setParameter('zone',  $user->getZone()->getId() );
        }
        if ($user->getBusinessUnit()) {
            $result->leftJoin('u.businessUnit','bu')
                ->andWhere('bu.id = :bu ')
                ->setParameter('bu',  $user->getBusinessUnit()->getId() );
        }
        $result = $result->orderBy('u.id', 'DESC')->getQuery()
            ->getResult();

        return $result;


    }
    public function getListAdminsDeleted($array)
    {
        if(array_key_exists("keyWord" ,  $array ))$keyword = $array['keyWord'];
        else $keyword="";

        if(array_key_exists("branch" ,  $array ))$branch = $array['branch'];
        else $branch="";
        if(array_key_exists("acces" ,  $array )) $acces = $array['acces'];
        else $acces="";
        if(array_key_exists("user" ,  $array ))$user = $array['user'];
        else $user="";

        $zone = "";
        if(array_key_exists("zone" ,  $array ))$zone = $array['zone'];


        $result = $this->createQueryBuilder('u')
            ->leftJoin('u.branch','b')
            ->leftJoin('u.acces','a')
            ->where('u.roles LIKE :roles and u.isDeleted = true ')
            ->setParameter('roles', '%ROLE_GESTIONNAIRE%')
        ;
        if ($user != "") {
            $result->andWhere('u.id != :user')
                ->setParameter('user',$user->getId());
        }
        if ($keyword != "") {
            $result->andWhere('u.email LIKE :keyword')
                ->setParameter('keyword', "%" . $keyword . "%");
        }
        if ($branch != "") {
            $result->andWhere('b.id = :branch ')
                ->setParameter('branch',  $branch );
        }
        if ($acces != "") {
            $result->andWhere('a.id = :acces ')
                ->setParameter('acces',  $acces );
        }
        if ($user->getZone()) {
            $result->leftJoin('u.zone','z')
                ->andWhere('z.id = :zone ')
                ->setParameter('zone',  $user->getZone()->getId() );
        }
        if ($user->getBusinessUnit()) {
            $result->leftJoin('u.businessUnit','bu')
                ->andWhere('bu.id = :bu ')
                ->setParameter('bu',  $user->getBusinessUnit()->getId() );
        }
        $result = $result->orderBy('u.id', 'DESC')->getQuery()
            ->getResult();

        return $result;


    }


    public function getListController($array)
    {
        if(array_key_exists("keyWord" ,  $array ))$keyword = $array['keyWord'];
        else $keyword="";

        if(array_key_exists("branch" ,  $array ))$branch = $array['branch'];
        else $branch="";
        if(array_key_exists("acces" ,  $array )) $acces = $array['acces'];
        else $acces="";
        if(array_key_exists("user" ,  $array ))$user = $array['user'];
        else $user="";
        $result = $this->createQueryBuilder('u')
            ->leftJoin('u.branch','b')
            ->leftJoin('u.acces','a')
            ->where('u.roles LIKE :roles and u.isDeleted = false ')
            ->setParameter('roles', '%ROLE_CONTROLLER%')
        ;
        if ($user != "") {
            $result->andWhere('u.id != :user')
                ->setParameter('user',$user->getId());
        }
        if ($keyword != "") {
            $result->andWhere('u.email LIKE :keyword')
                ->setParameter('keyword', "%" . $keyword . "%");
        }
        if ($branch != "") {
            $result->andWhere('b.id = :branch ')
                ->setParameter('branch',  $branch );
        }
        if ($acces != "") {
            $result->andWhere('a.id = :acces ')
                ->setParameter('acces',  $acces );
        }
        $result = $result->orderBy('u.id', 'DESC')->getQuery()
            ->getResult();

        return $result;


    }
    public function findBykeyword($array)
    {

        $keyword = "";
        if(array_key_exists("keyWord",$array))$keyword = $array['keyWord'];

        $role = "";
        if(array_key_exists("role",$array))$role = $array['role'];

        $result = $this->createQueryBuilder('g')
            ->where('g.isDeleted = false ');

        if ($keyword != "") {
            $result->andWhere('g.raisonSocial LIKE :keyword or  g.premierResponsable LIKE :keyword or  g.tel LIKE :keyword  or  g.email LIKE :keyword  or  g.gsm LIKE :keyword ')
                ->setParameter('keyword', "%" . $keyword . "%");
        }

        if($role != ""){
            $result->andwhere('g.roles LIKE :roles')
                ->setParameter('roles', "%".$role."%");
        }

        $user = "";
        $branch = "";
        $filiale = "";
        $businessLine = "";
        $localBusinessUnit = "";

        if (array_key_exists("user", $array)) $user = $array['user'];
        if ($user != "") {
            //$user = new User();
            if ($user->getBranch()) {
                $branch = $user->getBranch();
            }
            if ($user->getFiliale()) {
                $filiale = $user->getFiliale();
            }
            if ($user->getBusinessLine()) {
                $businessLine = $user->getBusinessLine();
            }
            if ($user->getLocalBusinessUnit()) {
                $localBusinessUnit = $user->getLocalBusinessUnit();
            }
        }


        if (array_key_exists("branch", $array)) $branch = $array['branch'];
        if (array_key_exists("filiale", $array)) $filiale = $array['filiale'];
        if (array_key_exists("businessLine", $array)) $businessLine = $array['businessLine'];
        if (array_key_exists("localBusinessUnit", $array)) $localBusinessUnit = $array['localBusinessUnit'];

        if ($filiale != "") {
            $result->andWhere('g.filiale = :filiale')
                ->setParameter('filiale', $filiale);
        }
        if ($businessLine != "") {
            $result->andWhere('g.businessLine = :businessLine')
                ->setParameter('businessLine', $businessLine);
        }
        if ($localBusinessUnit != "") {
            $result->andWhere('g.localBusinessUnit = :localBusinessUnit')
                ->setParameter('localBusinessUnit', $localBusinessUnit);
        }
        if ($branch != "") {
            $result->andWhere('g.branch = :branch')
                ->setParameter('branch', $branch);
        }


        $result = $result->orderBy('g.id', 'DESC')->getQuery()->getResult();



        return $result;


    }


    public function findByConditions($array)
    {

        $keyword = "";
        if (array_key_exists("keyWord", $array)) $keyword = $array['keyWord'];
        $type= "";
        if (array_key_exists("type", $array)) $type = $array['type'];

        $role = "";
        if (array_key_exists("role", $array)) $role = $array['role'];

        $result = $this->createQueryBuilder('g')
            ->where('g.isDeleted = false ');

        if ($keyword != "") {
            $result->andWhere('g.email LIKE :keyword')
                ->setParameter('keyword', "%" . $keyword . "%");
        }
        if ($type != "") {
            $result->andWhere('g.type_controller = :type_controller')
                ->setParameter('type_controller',  $type);
        }
        if ($role != "") {
            $result->andwhere('g.roles LIKE :roles and g.isDeleted = false ')
                ->setParameter('roles', "%" . $role . "%");
        }

        $user = "";
        $branch = "";
        $filiale = "";
        $businessLine = "";
        $localBusinessUnit = "";
        $zone = "";
        if (array_key_exists("user", $array)) $user = $array['user'];
        if ($user != "") {

            if ($user->getBranch()) {
                $branch = $user->getBranch();
            }
            if ($user->getFiliale()) {
                $filiale = $user->getFiliale();
            }
            if ($user->getBusinessLine()) {
                $businessLine = $user->getBusinessLine();
            }
            if ($user->getLocalBusinessUnit()) {
                $localBusinessUnit = $user->getLocalBusinessUnit();
            }
            if ($user->getZone()) {
                $zone = $user->getZone();
            }
        }


        if (array_key_exists("branch", $array)) $branch = $array['branch'];
        if (array_key_exists("filiale", $array)) $filiale = $array['filiale'];
        if (array_key_exists("businessLine", $array)) $businessLine = $array['businessLine'];
        if (array_key_exists("localBusinessUnit", $array)) $localBusinessUnit = $array['localBusinessUnit'];

        if ($filiale != "") {
            $result->andWhere('g.filiale = :filiale')
                ->setParameter('filiale', $filiale);
        }
        if ($businessLine != "") {
            $result->andWhere('g.businessLine = :businessLine')
                ->setParameter('businessLine', $businessLine);
        }
        if ($localBusinessUnit != "") {
            $result->andWhere('g.localBusinessUnit = :localBusinessUnit')
                ->setParameter('localBusinessUnit', $localBusinessUnit);
        }
        if ($branch != "") {
            $result->andWhere('g.branch = :branch')
                ->setParameter('branch', $branch);
        }
        if ($zone != "") {
            $result->andWhere('g.zone = :zone')
                ->setParameter('zone', $zone);
        }

        $result = $result->orderBy('g.id', 'DESC')->getQuery()->getResult();

        return $result;


    }
    public function findByConditionsDeleted($array)
    {

        $keyword = "";
        if (array_key_exists("keyWord", $array)) $keyword = $array['keyWord'];
        $type= "";
        if (array_key_exists("type", $array)) $type = $array['type'];
        $role = "";
        if (array_key_exists("role", $array)) $role = $array['role'];

        $result = $this->createQueryBuilder('g')
            ->where('g.isDeleted = true ');

        if ($keyword != "") {
            $result->andWhere('g.email LIKE :keyword')
                ->setParameter('keyword', "%" . $keyword . "%");
        }
        if ($type != "") {
        $result->andWhere('g.type_controller = :type_controller')
            ->setParameter('type_controller',  $type);
    }

        if ($role != "") {
            $result->andwhere('g.roles LIKE :roles and g.isDeleted = true ')
                ->setParameter('roles', "%" . $role . "%");
        }

        $user = "";
        $branch = "";
        $filiale = "";
        $businessLine = "";
        $localBusinessUnit = "";
        $zone = "";
        if (array_key_exists("user", $array)) $user = $array['user'];
        if ($user != "") {

            if ($user->getBranch()) {
                $branch = $user->getBranch();
            }
            if ($user->getFiliale()) {
                $filiale = $user->getFiliale();
            }
            if ($user->getBusinessLine()) {
                $businessLine = $user->getBusinessLine();
            }
            if ($user->getLocalBusinessUnit()) {
                $localBusinessUnit = $user->getLocalBusinessUnit();
            }
            if ($user->getZone()) {
                $zone = $user->getZone();
            }
        }


        if (array_key_exists("branch", $array)) $branch = $array['branch'];
        if (array_key_exists("filiale", $array)) $filiale = $array['filiale'];
        if (array_key_exists("businessLine", $array)) $businessLine = $array['businessLine'];
        if (array_key_exists("localBusinessUnit", $array)) $localBusinessUnit = $array['localBusinessUnit'];

        if ($filiale != "") {
            $result->andWhere('g.filiale = :filiale')
                ->setParameter('filiale', $filiale);
        }
        if ($businessLine != "") {
            $result->andWhere('g.businessLine = :businessLine')
                ->setParameter('businessLine', $businessLine);
        }
        if ($localBusinessUnit != "") {
            $result->andWhere('g.localBusinessUnit = :localBusinessUnit')
                ->setParameter('localBusinessUnit', $localBusinessUnit);
        }
        if ($branch != "") {
            $result->andWhere('g.branch = :branch')
                ->setParameter('branch', $branch);
        }
        if ($zone != "") {
            $result->andWhere('g.zone = :zone')
                ->setParameter('zone', $zone);
        }

        $result = $result->orderBy('g.id', 'DESC')->getQuery()->getResult();

        return $result;


    }

    // /**
    //  * @return User[] Returns an array of User objects
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
    public function findOneBySomeField($value): ?User
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
