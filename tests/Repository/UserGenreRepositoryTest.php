<?php

namespace App\Tests\Repository;

use App\Entity\UserGenre;
use App\Repository\UserGenreRepository;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * This test was written to test if the repository can save and remove data
 * Connecting to database slows down test, so instead a mock is created to test if save and remove works
 */
/**
 * @author Wout Houpeline
 * @since 2023-05-27
 */
class UserGenreRepositoryTest extends KernelTestCase
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

        $this->repository = new UserGenreRepository($managerRegistryMock);
    }

    public function testSave()
    {
        $UserGenre = new UserGenre();
        $this->entityManagerMock->expects($this->once())
            ->method('persist')
            ->with($UserGenre);
        $this->entityManagerMock->expects($this->once())
            ->method('flush');

        $this->repository->save($UserGenre, true);
    }


    public function testRemove()
    {
        $UserGenre = new UserGenre();
        $this->entityManagerMock->expects($this->once())
            ->method('remove')
            ->with($UserGenre);
        $this->entityManagerMock->expects($this->once())
            ->method('flush');

        $this->repository->remove($UserGenre, true);

    }

    // add more test methods as needed
}
