<?php

namespace App\Repository;

use App\Entity\BookReviews;
use App\Entity\Genre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BookReviews::class);
    }

    public function save(BookReviews $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(BookReviews $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    public function getReviews(int $offset, int $limit, int $bookId): array
    {

        //no query builder in order to use GROUP_CONCAT

        $sql = 'SELECT b.comment, b.score, b.date_added, u.display_name
                FROM book_reviews b, user as u
                WHERE u.id = b.user_id_id and b.book_id_id = '.$bookId.' 
                ORDER BY b.date_added DESC
                LIMIT '.$limit
                .' OFFSET '.$offset;

        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        return $stmt->executeQuery()->fetchAllAssociative();
//
//
//
//
//        $entityManager = $this->getEntityManager();
//
//        $query = $entityManager->createQuery(
//            'SELECT u.displayName, b.comment, b.score, b.dateAdded
//            FROM App\Entity\BookReviews b, App\Entity\User u
//            WHERE u.id = b.userId
//            ORDER BY b.dateAdded DESC
//            OFFSET 40'
//        );
////        LIMIT :limit
////            OFFSET :offset
//     // $query->setParameter('limit', $limit);
////        $query->setParameter('offset', $offset);
//
//        return $query->getResult();
    }
}
