<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\DBAL\FetchMode;
use Doctrine\ORM\EntityManagerInterface;
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
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    public function save(Book $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Book $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @throws \Doctrine\DBAL\Exception
     */
    public function findLimitedRecords(int $limit): array
    {
       // $repository = $this->getEntityManager()->getRepository(Book::class);

//        $queryBuilder = $this->getEntityManager()->createQueryBuilder();

//        $queryBuilder
//            ->select('e')
//            ->from(Book::class, 'e')
//            ->setMaxResults($limit);
//        $queryBuilder
//            ->select('b', 'GROUP_CONCAT(g.name SEPARATOR \', \') AS genres')
//            ->from('Book', 'b')
//            ->join('b.bookGenres', 'bg')
//            ->join('bg.genre', 'g')
//            ->groupBy('b.id');
        $sql = 'SELECT b.*, GROUP_CONCAT(g.genre SEPARATOR \', \') AS genres
        FROM book b
        JOIN book_genre bg ON b.id = bg.book_id_id
        JOIN genre g ON bg.genre_id_id = g.id
        GROUP BY b.id 
        limit 40';


        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        //$stmt->bindParam('lim', $limit);
        $result = $stmt->executeQuery()->fetchAllAssociative();
        return $result;

        return $result;
    }

    /**
     * @throws \Doctrine\DBAL\Exception
     */
    public function searchOnTitle(int $limit, String $searchTerm): array
    {
        // $repository = $this->getEntityManager()->getRepository(Book::class);

//        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
//
//        $queryBuilder
//            ->select('e')
//            ->from(Book::class, 'e')
//            ->where($queryBuilder->expr()->like('e.Title', ':searchTerm'))
//            ->setParameter('searchTerm', '%' . $searchTerm . '%')
//            ->setMaxResults($limit);

//        $sql = "SELECT b.*, GROUP_CONCAT(g.genre SEPARATOR \', \') AS genres
//        FROM book b
//        JOIN book_genre bg ON b.id = bg.book_id_id
//        JOIN genre g ON bg.genre_id_id = g.id
//        WHERE b.title like".'%'.$searchTerm.'%'."
//        GROUP BY b.id
//        limit 40";

        $sql = 'SELECT b.*, GROUP_CONCAT(g.genre SEPARATOR \', \') AS genres
        FROM book b
        JOIN book_genre bg ON b.id = bg.book_id_id
        JOIN genre g ON bg.genre_id_id = g.id
        WHERE b.title like "'."%".$searchTerm."%\"".'
        GROUP BY b.id 
        limit 40';

        //$searchTerm = '%'.$searchTerm.'%';

        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        //$stmt->bindParam('var', $searchTerm);
        $result = $stmt->executeQuery()->fetchAllAssociative();
        return $result;


        return $queryBuilder->getQuery()->getResult();
    }
}
