<?php

namespace App\Repository;

use App\Entity\ParticipationEvenement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ParticipationEvenement|null find($id, $lockMode = null, $lockVersion = null)
 * @method ParticipationEvenement|null findOneBy(array $criteria, array $orderBy = null)
 * @method ParticipationEvenement[]    findAll()
 * @method ParticipationEvenement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParticipationEvenementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ParticipationEvenement::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(ParticipationEvenement $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(ParticipationEvenement $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return ParticipationEvenement[] Returns an array of ParticipationEvenement objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ParticipationEvenement
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function countParticipation($id){
        return   $this->createQueryBuilder('a')
              ->where('a.idEvenement =:id')
             ->setParameter(':id',$id)
             ->select('count(a.id)')
             ->getQuery()
             ->getSingleScalarResult();



    }

    public function estParticipant($idU,$idE){
        return   $this->createQueryBuilder('a')
            ->where('a.idEvenement =:idE')
            ->andWhere('a.idUtilisateur=:idU')
            ->setParameter('idU',$idU)
            ->setParameter(':idE',$idE)
            ->select('count(a.id)')
            ->getQuery()
            ->getSingleScalarResult();



    }


}
