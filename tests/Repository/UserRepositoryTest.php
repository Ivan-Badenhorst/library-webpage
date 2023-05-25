<?php

namespace App\Tests\Repository;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserRepositoryTest extends KernelTestCase
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

        $this->repository = new UserRepository($managerRegistryMock);
    }

    public function testSave()
    {
        $User = new User();
        $this->entityManagerMock->expects($this->once())
            ->method('persist')
            ->with($User);
        $this->entityManagerMock->expects($this->once())
            ->method('flush');

        $this->repository->save($User, true);
    }


    public function testRemove()
    {
        $User = new User();
        $this->entityManagerMock->expects($this->once())
            ->method('remove')
            ->with($User);
        $this->entityManagerMock->expects($this->once())
            ->method('flush');

        $this->repository->remove($User, true);

    }

    // add more test methods as needed
}
