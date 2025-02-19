<?php
/**
 * @fileoverview Repository for the UserGenre table in the database, contains methods to save or remove entities into the table
 * This table in the database stores what genres a user is interested in
 * @version 1.0
 */

/**
 * @author Aymeric Baume
 * @since 2023-04-28.
 */

namespace App\Repository;

use App\Entity\UserGenre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserGenre>
 *
 * @method UserGenre|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserGenre|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserGenre[]    findAll()
 * @method UserGenre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserGenreRepository extends ServiceEntityRepository
{
    /**
     * Constructs a new instance of the database repository.
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserGenre::class);
    }

    /**
     * Used to add a new user genre to the database
     *
     * @param UserGenre $entity -> New UserGenre to be added to the database.
     * @param bool $flush (optional) -> indicates if change will be synchronized to the database. Default = false.
     */
    public function save(UserGenre $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Used to remove a user genre from the database
     *
     * @param UserGenre $entity -> UserGenre to be removed from the database.
     * @param bool $flush (optional) -> indicates if change will be synchronized to the database. Default = false.
     */
    public function remove(UserGenre $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
//    /**
//     * @return UserGenre[] Returns an array of UserGenre objects
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

//    public function findOneBySomeField($value): ?UserGenre
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
