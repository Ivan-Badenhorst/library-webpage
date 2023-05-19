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
        $limit = 20;
        $expectedResult = [
            'The Hunger Games',
            'The Valley of Horses',
            'Headlong Hall',
            'Ape and Essence',
            'Mr Ape',
            'AP Advantage',
            'Ape Adventures (DK READERS)',
            'Aping language',
            'Apes and Angels',
            'AP biology',
            'Monkeys & apes',
            'Monkeys & apes',
            'The Talking Ape',
            'Abe Anjin',
            'Hitoshi Abe flicker',
            'Cubase SX/SL',
            "Intel's SL architecture",
            'Mercedes-Benz SL',
            "Can S&Ls Survive?",
            'Trees'
        ];; // Set your expected result here

        $queryBuilderMock = $this->createMock(\Doctrine\ORM\QueryBuilder::class);
        $queryMock = $this->createMock(\Doctrine\ORM\AbstractQuery::class);

        $this->entityManagerMock->expects($this->once())
            ->method('createQueryBuilder')
            ->willReturn($queryBuilderMock);

        $queryBuilderMock->expects($this->once())
            ->method('select')
            ->with('e')
            ->willReturnSelf();
        $queryBuilderMock->expects($this->once())
            ->method('from')
            ->with(Book::class, 'e')
            ->willReturnSelf();
        $queryBuilderMock->expects($this->once())
            ->method('setMaxResults')
            ->with($limit)
            ->willReturnSelf();
        $queryBuilderMock->expects($this->once())
            ->method('getQuery')
            ->willReturn($queryMock);

        $queryMock->expects($this->once())
            ->method('getResult')
            ->willReturn($expectedResult);

        $result = $this->repository->findLimitedRecords($limit);
        $this->assertEquals($expectedResult, $result);
        /*for ($i = 0; $i <= 20; $i++) {
            $this->assertEquals($expectedResult[$i], $result[$i].getTitle());
        }*/
    }

 /*   public function testSearchOnTitle(): void
    {
        $limit = 20;
        $searchTerm = 'ape';
        $expectedResult = [
            'Ape and Essence',
            'Mr Ape',
            'Ape Adventures (DK READERS)',
            'Apes and Angels',
            'Monkeys & apes',
            'Monkeys & apes',
            'The Talking Ape',
            'Estetické problémy pod napětím'
        ];; // Set your expected result here

        $queryBuilderMock = $this->createMock(\Doctrine\ORM\QueryBuilder::class);
        $queryMock = $this->createMock(\Doctrine\ORM\AbstractQuery::class);

        $this->entityManagerMock->expects($this->once())
            ->method('createQueryBuilder')
            ->willReturn($queryBuilderMock);

        $queryBuilderMock->expects($this->once())
            ->method('select')
            ->with('e')
            ->willReturnSelf();
        $queryBuilderMock->expects($this->once())
            ->method('from')
            ->with(Book::class, 'e')
            ->willReturnSelf();
        $queryBuilderMock->expects($this->once())
            ->method('where')
            ->with($queryBuilderMock->expr()->like('e.title', ':searchTerm'))
            ->willReturnSelf();
        $queryBuilderMock->expects($this->once())
            ->method('setParameter')
            ->with('searchTerm', '%' . $searchTerm . '%')
            ->willReturnSelf();
        $queryBuilderMock->expects($this->once())
            ->method('setMaxResults')
            ->with($limit)
            ->willReturnSelf();
        $queryBuilderMock->expects($this->once())
            ->method('getQuery')
            ->willReturn($queryMock);

        $queryMock->expects($this->once())
            ->method('getResult')
            ->willReturn($expectedResult);

        $result = $this->repository->searchOnTitle($limit, $searchTerm);
        $this->assertEquals($expectedResult, $result);
    }*/

}
