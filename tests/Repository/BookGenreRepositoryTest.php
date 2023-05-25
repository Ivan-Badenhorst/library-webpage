<?php

namespace App\Tests\Repository;

use App\Entity\BookGenre;
use App\Repository\BookGenreRepository;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class BookGenreRepositoryTest extends KernelTestCase
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

        $this->repository = new BookGenreRepository($managerRegistryMock);
    }

    public function testSave()
    {
        $bookGenre = new BookGenre();
        $this->entityManagerMock->expects($this->once())
            ->method('persist')
            ->with($bookGenre);
        $this->entityManagerMock->expects($this->once())
            ->method('flush');

        $this->repository->save($bookGenre, true);
    }


    public function testRemove()
    {
        $bookGenre = new BookGenre();
        $this->entityManagerMock->expects($this->once())
            ->method('remove')
            ->with($bookGenre);
        $this->entityManagerMock->expects($this->once())
            ->method('flush');

        $this->repository->remove($bookGenre, true);

    }

    // add more test methods as needed
}
