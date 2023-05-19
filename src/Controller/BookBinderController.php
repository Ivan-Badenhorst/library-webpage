<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\User;
use App\Entity\UserBook;
use App\Repository\BookRepository;
use App\Repository\UserBookRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BookBinderController extends AbstractController
{
    private array $stylesheets;

    public function __construct()
    {
        $this->stylesheets[] = 'base.css';
    }

    #[Route('/', name: 'home')]
    public function home(): Response
    {
        $this->stylesheets[] = 'main.css';
        return $this->render('main.html.twig', [
            'stylesheets'=> $this->stylesheets
        ]);
    }

    #[Route("/book-info", name: "bookinfo")]
    public function addToList(Request $request, BookRepository $bookRepository, UserRepository $userRepository, UserBookRepository $userBookRepository): Response {
        // create form
        // ref : https://symfony.com/doc/current/forms.html
        $userBook = new UserBook();
        $form = $this->createFormBuilder($userBook)
            //->add('bookId',IntegerType::class, ['label' => 'bookID'])
            //->add('userId',IntegerType::class, ['label' => 'userID'])
            ->add('save',SubmitType::class, ['label' => 'Add to favorites'])
            ->getForm();

        // check if form was submitted and handle data
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //$book_id = $form->getData()['bookId'];
            //$user_id = $form->getData()['userId'];
            $book = $bookRepository->findBook(26);
            $user = $userRepository->findUser(15);
            $userBook->setBookId($book);
            $userBook->setUserId($user);
            $userBookRepository->save($userBook, true);
            return $this->redirectToRoute('home');
        }

        $this->stylesheets[] = 'bookinfo.css';
        return $this->render('bookInfo.html.twig', [
            'stylesheets'=> $this->stylesheets,
            'userBook_form'=>$form
        ]);
    }

}