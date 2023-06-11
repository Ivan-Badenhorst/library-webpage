<?php
/**
 * @fileoverview Repository for the Book table in the database, contains methods to save or remove entities into the table
 * This table in the database stores the books and their relevant information.
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
     * Used to add a new book to the database
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
     * Used to remove a book from the database
     *
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

    /**
     * Returns a book that matches the book ID provided
     *
     * @param int $bookID -> id of the book in the database
     * @return Book -> entity that matches the id
     */
    public function findBook(int $bookID): Book
    {
        // $repository = $this->getEntityManager()->getRepository(Book::class);
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT b
            FROM App\Entity\Book b
            WHERE b.id = :bookID'
        )->setParameter('bookID', $bookID);

        return $query->getResult()[0];
    }

    /**
     * Returns the average rating of a book in the database
     *
     * @param int $bookID -> id of the book in the database
     * @return int -> average rating
     */
    public function findBookRating(int $bookID): int
    {
        // $repository = $this->getEntityManager()->getRepository(Book::class);
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT AVG(r.score) AS average_score
            FROM App\Entity\BookReviews AS r
            WHERE r.bookId = :bookID'
        )->setParameter('bookID', $bookID);

        $result = $query->getSingleScalarResult();

        if($result != 0){
            return $result;
        }
        else{
            return 0;
        }
    }

    /**
     * Returns a list of books based on a search term
     *
     * @param int $limit -> maximum number of record returned
     * @param String $searchTerm -> term used to search for books. Term must be included in the title for the book to
     *                              qualify
     * @return array -> array of books qualifying for the search term
     * @throws Exception
     */
    public function searchOnTitle(int $limit, String $searchTerm, array $genres, int $offset = 0): array
    {
        //no query builder in order to use GROUP_CONCAT
        $sql = 'SELECT b.*, GROUP_CONCAT(g.genre SEPARATOR \', \') AS genres
        FROM book b
        JOIN book_genre bg ON b.id = bg.book_id_id
        JOIN genre g ON bg.genre_id_id = g.id'.' WHERE b.title like "'."%".$searchTerm."%\"";

        $sql = $sql.' GROUP BY b.id ';

        if(count($genres) > 0){
            $sql = $sql.'HAVING ';
        }

        $count = 0;
        foreach ($genres as $genre){
            if($count > 0){
                $sql = $sql.' or ';
            }
            $count += 1;
            $sql = $sql.'genres LIKE \'%'.$genre.'%\'';
        }

        $sql = $sql.' limit '.$limit.' offset '.$offset;

        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        return $stmt->executeQuery()->fetchAllAssociative();
    }

    public function findFavourites(int $limit)
    {
        $sql = "SELECT book.*, AVG(review.score) as average_score
                FROM book, book_reviews as review
                WHERE book.id = review.book_id_id
                GROUP BY book.id
                ORDER BY average_score DESC
                LIMIT ".$limit;

        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        return $stmt->executeQuery()->fetchAllAssociative();

//        $queryBuilder = $this->createQueryBuilder('n');
//        $queryBuilder->select('book.id, book.bookCover, AVG(review.score) as average_score')
//            ->from('App\Entity\Book', 'book')
//            ->join('App\Entity\BookReviews', 'review', 'WITH', 'book.id=review.bookId')
//            ->groupBy('book.id')
//            ->orderBy('average_score', 'DESC')
//            ->setMaxResults($limit);
//
//        return $queryBuilder->getQuery()->getResult();

    }
}
