<?php

namespace App\Repository;

use App\Entity\CommentaireBlog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CommentaireBlog|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommentaireBlog|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommentaireBlog[]    findAll()
 * @method CommentaireBlog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentaireBlogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommentaireBlog::class);
    }

    // /**
    //  * @return CommentaireBlog[] Returns an array of CommentaireBlog objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CommentaireBlog
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    function listComByBlog($id){
        return $this->createQueryBuilder('re')
            ->join('re.idBlog','res')
            ->addSelect('re')
            ->where('res.idBlog=:id')
            ->setParameter('id',$id)
            ->getQuery()->getResult();
    }

    function listCommentaireBlogbycategorie($categorieCommentaireBlog){
        return $this->createQueryBuilder('r')
            ->where('r.categorieCommentaireBlog LIKE :chinois')
            ->setParameter('chinois',$categorieCommentaireBlog)
            ->getQuery()->getResult();
    }
}