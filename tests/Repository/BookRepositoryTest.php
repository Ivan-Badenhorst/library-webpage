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

}
