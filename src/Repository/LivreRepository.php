<?php

namespace App\Repository;

use App\Entity\Livre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Livre|null find($id, $lockMode = null, $lockVersion = null)
 * @method Livre|null findOneBy(array $criteria, array $orderBy = null)
 * @method Livre[]    findAll()
 * @method Livre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LivreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Livre::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Livre $entity, bool $flush = true): void
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
    public function remove(Livre $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return Livre[] Returns an array of Livre objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Livre
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function searchLivre(string $search, $filters=null)
    {
        $qb = $this->createQueryBuilder('a');
dump("/////////////////////////////////////");
dump($filters);
        dump("/////////////////////////////////////");
       $qb->innerJoin('App\Entity\Utilisateur', 'u', 'WITH', 'a.idEcrivainLivre = u.idUtilisateur');
       $qb->innerJoin('App\Entity\CategorieLivre', 'c', 'WITH', 'a.idCategorieLivre = c.idCategorieLivre');
        $qb->where(
                $qb->expr()->andX(
                    $qb->expr()->orX(
                        $qb->expr()->like('a.titreLivre', ':query')
                    )
                )
            )
            ->setParameter('query', $search ."%" );

        dump($qb);
        if($filters !=null ) {
            $qb->andWhere("a.idCategorieLivre in(:types)")
                ->setParameter(':types', array_values($filters));
        }
        return $qb->getQuery()->getResult();
    }

    public function countLivreParCategorie($id){
        return   $this->createQueryBuilder('a')
            ->where('a.idCategorieLivre =:id')
            ->setParameter(':id',$id)
            ->select('count(a.idLivre)')
            ->getQuery()
            ->getSingleScalarResult();



    }
}
