<?php

namespace App\Repository;

use App\Entity\Recomponse;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
class RecomponseRepository extends EntityRepository
{




    public function findEntitiesByString($str){
        return $this->getEntityManager()
            ->createQuery(
                'SELECT p
                FROM App\Entity\Recomponse p
                WHERE p.nomRecomponse LIKE :str'
            )
            ->setParameter('str', '%'.$str.'%')
            ->getResult();
    }

}