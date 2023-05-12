<?php
/**
 * @fileoverview Database repository for the Book table
 * @version 1.2
 */

/**
 * @author Ivan Badenhorst, Aymeric Baume
 * @since 2023-05-09.
 */

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Book>
 *
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{
    /**
     * Constructs a new instance of the database repository.
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    /**
     * Used to add a new genre to the database
     *
     * @param Book $entity -> New book to be added to the database.
     * @param bool $flush (optional) -> indicates if change will be synchronized to the database. Default = false.
     */
    public function save(Book $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @param Book $entity -> Book to be removed from the database.
     * @param bool $flush (optional) -> indicates if change will be synchronized to the database. Default = false.
     */
    public function remove(Book $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Returns a list of random books from the database
     *
     * @param int $limit -> maximum number of record returned
     * @return array -> array of random books
     * @throws Exception
     */
    public function findLimitedRecords(int $limit): array
    {
        //no query builder in order to use GROUP_CONCAT
        $sql = 'SELECT b.*, GROUP_CONCAT(g.genre SEPARATOR \', \') AS genres
                FROM book b
                JOIN book_genre bg ON b.id = bg.book_id_id
                JOIN genre g ON bg.genre_id_id = g.id
                GROUP BY b.id 
                limit '.$limit;

        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        return $stmt->executeQuery()->fetchAllAssociative();
    }
    public function findBook(int $bookID): Book
    {
        // $repository = $this->getEntityManager()->getRepository(Book::class);

        $queryBuilder = $this->getEntityManager()->createQueryBuilder();

        $queryBuilder
            ->select('e')
            ->from(Book::class, 'e')
            ->where('id',$bookID)
            ->getFirstResult();

        return $queryBuilder->getQuery()->getResult();
    }

//    /**
//     * @return Book[] Returns an array of Book objects
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

    /**
     * Returns a list of books based on a search term
     *
     * @param int $limit -> maximum number of record returned
     * @param String $searchTerm -> term used to search for books. Term must be included in the title for the book to
     *                              qualify
     * @return array -> array of books qualifying for the search term
     * @throws Exception
     */
    public function searchOnTitle(int $limit, String $searchTerm): array
    {
        //no query builder in order to use GROUP_CONCAT
        $sql = 'SELECT b.*, GROUP_CONCAT(g.genre SEPARATOR \', \') AS genres
        FROM book b
        JOIN book_genre bg ON b.id = bg.book_id_id
        JOIN genre g ON bg.genre_id_id = g.id
        WHERE b.title like "'."%".$searchTerm."%\"".'
        GROUP BY b.id 
        limit '.$limit;

        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        return $stmt->executeQuery()->fetchAllAssociative();
    }
}
