<?php
/**
 * @fileoverview Database repository for the Genre table
 * @version 1.1
 */

/**
 * @author Ivan Badenhorst, Aymeric Baume
 * @since 2023-05-09.
 */


namespace App\Repository;

use App\Entity\Genre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Genre>
 *
 * @method Genre|null find($id, $lockMode = null, $lockVersion = null)
 * @method Genre|null findOneBy(array $criteria, array $orderBy = null)
 * @method Genre[]    findAll()
 * @method Genre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GenreRepository extends ServiceEntityRepository
{

    /**
     * Constructs a new instance of the database repository.
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Genre::class);
    }


    /**
     * Used to add a new genre to the database
     *
     * @param Genre $entity -> New genre to be added to the database.
     * @param bool $flush (optional) -> indicates if change will be synchronized to the database. Default = false.
     * @return void
     */
    public function save(Genre $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    /**
     * Used to delete a genre from the database
     *
     * @param Genre $entity -> New genre to be removed from the database.
     * @param bool $flush (optional) -> indicates if change will be synchronized to the database. Default = false.
     * @return void
     */
    public function remove(Genre $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    /**
     * Returns a list of all genres elements in the database.
     *
     * @returns Genre[] an array of all Genre objects present in the database.
    */
    public function getGenre(): array
    {
        return $this->createQueryBuilder('e')
            ->select('e.genre')
            ->getQuery()
            ->getResult();
    }


}
