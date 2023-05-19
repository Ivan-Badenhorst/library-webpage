<?php
namespace App\Tests\Repository;

use App\Entity\Book;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\TestCase;

class BookRepositoryTest extends TestCase
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

        $this->repository = new BookRepository($managerRegistryMock);
    }

    public function testSave(): void
    {
        $book = new Book();
        $book->setTitle('Test Book');

        $this->entityManagerMock->expects($this->once())
            ->method('persist')
            ->with($book);
        $this->entityManagerMock->expects($this->once())
            ->method('flush');

        $this->repository->save($book, true);
    }

    public function testRemove(): void
    {
        $book = new Book();
        $book->setTitle('Test Book');

        $this->entityManagerMock->expects($this->once())
            ->method('remove')
            ->with($book);
        $this->entityManagerMock->expects($this->once())
            ->method('flush');

        $this->repository->remove($book, true);
    }



    public function testFindLimitedRecords(): void
    {
        $limit = 5;
        $expectedResult = [
            // Array of expected results
        ];

        $queryBuilder = $this->getMockBuilder(\Doctrine\ORM\QueryBuilder::class)
            ->disableOriginalConstructor()
            ->getMock();

        $queryBuilder->expects($this->once())
            ->method('select')
            ->with('e');

        $queryBuilder->expects($this->once())
            ->method('from')
            ->with(Book::class, 'e');

        $queryBuilder->expects($this->once())
            ->method('setMaxResults')
            ->with($limit);

        $query = $this->getMockBuilder(\Doctrine\ORM\AbstractQuery::class)
            ->disableOriginalConstructor()
            ->getMock();

        $queryBuilder->expects($this->once())
            ->method('getQuery')
            ->willReturn($query);

        $query->expects($this->once())
            ->method('getResult')
            ->willReturn($expectedResult);

        $this->entityManager->expects($this->once())
            ->method('createQueryBuilder')
            ->willReturn($queryBuilder);

        $repository = new YourDatabaseRepository($this->entityManager);
        $result = $repository->findLimitedRecords($limit);

        $this->assertEquals($expectedResult, $result);
    }
}
