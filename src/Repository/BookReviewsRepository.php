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


    public function getReviews(int $offset, int $limit): array
    {
        $queryBuilder = $this->createQueryBuilder('r');
        $queryBuilder
            ->select('r')
            ->from(BookReviews::class, 'r')
            ->orderBy('r.dateAdded', 'DESC')
            ->setMaxResults($limit)
            ->setFirstResult($offset);

        $query = $queryBuilder->getQuery();
        $query->setHydrationMode(AbstractQuery::HYDRATE_OBJECT);
        return $query->getResult();
    }
}
