<?php

namespace App\backend;

use App\Entity\Book;
use App\Entity\UserBook;
use Doctrine\ORM\EntityManagerInterface;

class ReadingList
{
    private \App\Repository\UserBookRepository $UserBookRepository;
    private \App\Repository\BookRepository $BookRepository;
    private EntityManagerInterface $entityManager;
    private auth $auth;

    function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->UserBookRepository = $this->entityManager->getRepository(UserBook::class);
        $this->BookRepository = $this->entityManager->getRepository(Book::class);
        $this->auth = new \App\backend\auth($this->entityManager);
    }

    public function getReadingList(String $email){
        $userID = $this->auth->getID($email);
        if($userID == null){
            return null;
        }
        if($this->UserBookRepository->findOneBy(['userId' => $userID]) == null){
            return null;
        }
        $listOfUserBooks = $this->UserBookRepository->findBy(['userId' => $userID]);
        $listOfIDs = [];
        $listOfTitle = [];
        $listOfSummary = [];
        $ListOfAuthor = [];
        $ListOfCover = [];
        foreach($listOfUserBooks as $item){

            $book = $this->BookRepository->findOneBy(['id' => $item->getBookId()]);
            if (!in_array($book->getId(), $listOfIDs)) {
                $listOfIDs[] = $book->getId();
                $listOfTitle[] = $book->getTitle();
                $listOfSummary[] = $book->getSummary();
                $ListOfAuthor[] = $book->getAuthor();
                $listOfCover[] = $book->getBookCover();
            }

        }
        return [$listOfIDs,$listOfTitle , $listOfSummary, $ListOfAuthor, $listOfCover];
    }

}