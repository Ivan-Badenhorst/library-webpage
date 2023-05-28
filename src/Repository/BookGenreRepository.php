<?php
/**
 * @fileoverview Repository for the BookGenre table in the database, contains methods to save or remove entities into the table
 * This table in the database stores what genres a book fits into
 * @version 1.0
 */

/**
 * @author Aymeric Baume
 * @since 2023-04-28.
 */

namespace App\Repository;

use App\Entity\BookGenre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BookGenre>
 *
 * @method BookGenre|null find($id, $lockMode = null, $lockVersion = null)
 * @method BookGenre|null findOneBy(array $criteria, array $orderBy = null)
 * @method BookGenre[]    findAll()
 * @method BookGenre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookGenreRepository extends ServiceEntityRepository
{
    /**
     * Constructs a new instance of the database repository.
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BookGenre::class);
    }

    /**
     * Used to add a new book genre to the database
     *
     * @param BookGenre $entity -> New book genre to be added to the database.
     * @param bool $flush (optional) -> indicates if change will be synchronized to the database. Default = false.
     */
    public function save(BookGenre $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Used to remove a book genre from the database
     *
     * @param BookGenre $entity -> BookGenre to be removed from the database.
     * @param bool $flush (optional) -> indicates if change will be synchronized to the database. Default = false.
     */
    public function remove(BookGenre $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return BookGenre[] Returns an array of BookGenre objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?BookGenre
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
