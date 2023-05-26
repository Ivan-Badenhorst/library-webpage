<?php
/**
 * @fileoverview Repository for the BookReviews table in the database, contains methods to save or remove entities into the table
 * This table in the database stores what genres a book fits into
 * @version 1.0
 */

/**
 * @author Aymeric Baume, Ivan Badenhorst
 * @since 2023-05-25.
 */

namespace App\Repository;

use App\Entity\BookReviews;
use App\Entity\Genre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BookReviews>
 *
 * @method BookReviews|null find($id, $lockMode = null, $lockVersion = null)
 * @method BookReviews|null findOneBy(array $criteria, array $orderBy = null)
 * @method BookReviews[]    findAll()
 * @method BookReviews[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookReviewsRepository extends ServiceEntityRepository
{

    /**
     * Constructs a new instance of the database repository.
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BookReviews::class);
    }


    /**
     * Used to add a new reviews to a book in the database
     *
     * @param BookReviews $entity -> New review to be added to the database.
     * @param bool $flush (optional) -> indicates if change will be synchronized to the database. Default = false.
     */
    public function save(BookReviews $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    /**
     * Used to delete reviews from a book
     *
     * @param BookReviews $entity -> Book review to be removed from the database
     * @param bool $flush (optional) -> indicates if change will be synchronized to the database. Default = false.
     */
    public function remove(BookReviews $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }



    /**
     * used to get a list of reviews for a specific book
     *
     * @param int $offset -> offset on the results
     * @param int $limit -> max number of reviews returned
     * @param int $bookId -> bookId used to get reviews
     * @return array -> array of review objects
     * @throws Exception
     */
    public function getReviews(int $offset, int $limit, int $bookId): array
    {

        //no query builder in order to use limit and offset
        $sql = 'SELECT b.comment, b.score, b.date_added, u.display_name
                FROM book_reviews b, user as u
                WHERE u.id = b.user_id_id and b.book_id_id = '.$bookId.' 
                ORDER BY b.date_added DESC
                LIMIT '.$limit
                .' OFFSET '.$offset;

        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        return $stmt->executeQuery()->fetchAllAssociative();

    }
}
