<?php


namespace App\Tests\Repository;

use App\Entity\BookReviews;
use App\Repository\BookReviewsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * This test was written to test if the repository can save and remove data
 * Connecting to database slows down test, so instead a mock is created to test if save and remove works
 */
/**
 * @author Wout Houpeline
 * @since 2023-05-27
 */
class BookReviewsRepositoryTest extends KernelTestCase
{
    private $entityManagerMock;
    private $repository;

    protected function setUp(): void
    {
        $classMetadataMock = $this->createMock(\Doctrine\ORM\Mapping\ClassMetadata::class);
        $this->entityManagerMock = $this->createMock(EntityManagerInterface::class);
        $this->entityManagerMock->method('getClassMetadata')->willReturn($classMetadataMock);
        $managerRegistryMock = $this->createMock(ManagerRegistry::class);
        $managerRegistryMock->method('getManagerForClass')->willReturn($this->entityManagerMock);

        $this->repository = new BookReviewsRepository($managerRegistryMock);
    }

    public function testSave()
    {
        $bookReview = new BookReviews();
        $this->entityManagerMock->expects($this->once())
            ->method('persist')
            ->with($bookReview);
        $this->entityManagerMock->expects($this->once())
            ->method('flush');

        $this->repository->save($bookReview, true);
    }

    public function testRemove()
    {
        $bookReview = new BookReviews();
        $this->entityManagerMock->expects($this->once())
            ->method('remove')
            ->with($bookReview);
        $this->entityManagerMock->expects($this->once())
            ->method('flush');

        $this->repository->remove($bookReview, true);
    }

}
