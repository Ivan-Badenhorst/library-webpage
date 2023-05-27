<?php

namespace App\Tests\Repository;

use App\Entity\Genre;
use App\Repository\GenreRepository;
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
class GenreRepositoryTest extends KernelTestCase
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

        $this->repository = new GenreRepository($managerRegistryMock);
    }

    public function testSave()
    {
        $Genre = new Genre();
        $this->entityManagerMock->expects($this->once())
            ->method('persist')
            ->with($Genre);
        $this->entityManagerMock->expects($this->once())
            ->method('flush');

        $this->repository->save($Genre, true);
    }


    public function testRemove()
    {
        $Genre = new Genre();
        $this->entityManagerMock->expects($this->once())
            ->method('remove')
            ->with($Genre);
        $this->entityManagerMock->expects($this->once())
            ->method('flush');

        $this->repository->remove($Genre, true);

    }
}
