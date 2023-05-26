<?php
/**
 * @fileoverview Repository for the User table in the database, contains methods to save or remove entities into the table
 * This table in the database stores all users registered in the website
 * @version 1.2
 */

/**
 * @author Aymeric Baume
 * @since 2023-04-28.
 */

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<user>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    /**
     * Constructs a new instance of the database repository.
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to add a new user to the database
     *
     * @param User $entity -> New user to be added to the database.
     * @param bool $flush (optional) -> indicates if change will be synchronized to the database. Default = false.
     */
    public function save(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Used to remove a user from the database
     *
     * @param User $entity -> User to be removed from the database.
     * @param bool $flush (optional) -> indicates if change will be synchronized to the database. Default = false.
     */
    public function remove(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Returns a user that matches the user ID provided
     *
     * @param int $userID -> id of the user in the database
     * @return User -> entity that matches the id
     */
    public function findUser(int $userID): User
    {
        // $repository = $this->getEntityManager()->getRepository(Book::class);
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT u
            FROM App\Entity\User u
            WHERE u.id = :userID'
        )->setParameter('userID', $userID);

        return $query->getResult()[0];
    }

//    /**
//     * @return user[] Returns an array of user objects
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

//    public function findOneBySomeField($value): ?user
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
