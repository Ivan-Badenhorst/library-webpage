<?php

namespace App\Tests\Repository;

use App\Entity\UserBook;
use App\Repository\UserBookRepository;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserBookRepositoryTest extends KernelTestCase
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

        $this->repository = new UserBookRepository($managerRegistryMock);
    }

    public function testSave()
    {
        $UserBook = new UserBook();
        $this->entityManagerMock->expects($this->once())
            ->method('persist')
            ->with($UserBook);
        $this->entityManagerMock->expects($this->once())
            ->method('flush');

        $this->repository->save($UserBook, true);
    }


    public function testRemove()
    {
        $UserBook = new UserBook();
        $this->entityManagerMock->expects($this->once())
            ->method('remove')
            ->with($UserBook);
        $this->entityManagerMock->expects($this->once())
            ->method('flush');

        $this->repository->remove($UserBook, true);

    }

    // add more test methods as needed
}
