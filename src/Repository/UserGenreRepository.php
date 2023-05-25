<?php

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
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserGenre::class);
    }

    public function save(UserGenre $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(UserGenre $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

}
