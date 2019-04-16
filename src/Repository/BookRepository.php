<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Book::class);
    }

    public function orderAsc()
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.title IS NOT NULL')
            ->orderBy('b.title', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function announcementWithoutImage()
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.image IS NULL')
            ->getQuery()
            ->getResult()
            ;
    }

    public function searchBook($criteria)
    {
        return $this->createQueryBuilder('b')
            ->leftJoin('b.authors', 'authors')
            ->where('authors.name = :authorsName')
            ->setParameter('authorsName', $criteria['authors']->getName())
            ->andWhere('b.title = :title')
            ->setParameter('title', $criteria['title']->getTitle())
    //        ->andWhere('b.image = :image')
    //        ->setParameter('image', $criteria['image'])
            ->getQuery()
            ->getResult()
            ;
    }

    // /**
    //  * @return Book[] Returns an array of Book objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Book
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
