<?php
/**
 * @fileoverview Repository for the UserBook table in the database, contains methods to save or remove entities into the table
 * This table in the database stores the books that all users have added to their reading lists
 * @version 1.0
 */

/**
 * @author Aymeric Baume
 * @since 2023-04-28.
 */

namespace App\Repository;

use App\Entity\UserBook;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserBook>
 *
 * @method UserBook|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserBook|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserBook[]    findAll()
 * @method UserBook[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserBookRepository extends ServiceEntityRepository
{
    /**
     * Constructs a new instance of the database repository.
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserBook::class);
    }

    /**
     * Used to add a new user book to the database
     *
     * @param UserBook $entity -> New UserBook to be added to the database.
     * @param bool $flush (optional) -> indicates if change will be synchronized to the database. Default = false.
     */
    public function save(UserBook $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Used to remove a user book from the database
     *
     * @param UserBook $entity -> UserBook to be removed from the database.
     * @param bool $flush (optional) -> indicates if change will be synchronized to the database. Default = false.
     */
    public function remove(UserBook $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Checks if a UserBook that matches the book ID and user ID provided exists
     *
     * @param int $bookID -> id of the book in the database
     * @param int $userID -> id of the user logged in
     * @return bool -> whether it exists in the database or not
     */
    public function check(int $bookID, int $userID): bool
    {
        $exists = false;
        // $repository = $this->getEntityManager()->getRepository(Book::class);
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT b
            FROM App\Entity\UserBook b
            WHERE b.bookId = :bookID AND b.userId = :userID'
        )->setParameter('bookID', $bookID);
        $query->setParameter('userID', $userID);

        if($query->getResult() != null){
            $exists = true;
        }

        return $exists;
    }

    /**
     * Returns a UserBook that matches the book ID and user ID provided
     *
     * @param int $bookID -> id of the book in the database
     * @param int $userID -> id of the user logged in
     * @return UserBook -> entity that matches the id
     */
    public function findUserBook(int $bookID, int $userID): UserBook
    {
        $exists = false;
        // $repository = $this->getEntityManager()->getRepository(Book::class);
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT b
            FROM App\Entity\UserBook b
            WHERE b.bookId = :bookID AND b.userId = :userID'
        )->setParameter('bookID', $bookID);
        $query->setParameter('userID', $userID);

        return $query->getResult()[0];
    }

//    /**
//     * @return UserBook[] Returns an array of UserBook objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?UserBook
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
