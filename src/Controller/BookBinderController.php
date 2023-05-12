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
use App\Form\BookSearch;
use App\Repository\BookRepository;
use App\Repository\GenreRepository;
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

    /**
     * Generates the home page. All genres from the database are displayed along with a random list of 40 books
     *
     * @param Request $request
     * @param BookRepository $bookRepository
     * @param GenreRepository $genreRepository
     * @return Response -> home page of the website
     * @throws \Doctrine\DBAL\Exception
     */
    #[Route('/', name: 'home')]
    public function home(Request $request, BookRepository $bookRepository, GenreRepository $genreRepository): Response
    {
        //gets a list of all genres as string
        $genres_qry = $genreRepository->getGenre();
        $genres = [];
        foreach ($genres_qry as $genre){

            $genres[] = $genre['genre'];
        }

        //creates a form used as a search bar
        $form = $this->createForm(BookSearch::class);
        $this->stylesheets[] = 'main.css';

        //gets a list of 40 random books
        $products = $bookRepository->findLimitedRecords(40);

        return $this->render('main.html.twig', [
            'genres'=> $genres,
            'form' => $form->createView(),
            'stylesheets'=> $this->stylesheets,
            'books'=>$products
        ]);
    }

    #[Route("/book-info", name: "bookinfo")]
    public function addToList(Request $request, BookRepository $bookRepository, UserRepository $userRepository, UserBookRepository $userBookRepository): Response {
        // create form
        // ref : https://symfony.com/doc/current/forms.html
        $userBook = new UserBook();
        $form = $this->createFormBuilder($userBook)
            ->add('book__Id',IntegerType::class, ['label' => 'bookID'])
            ->add('user__Id',IntegerType::class, ['label' => 'userID'])
            ->add('save',SubmitType::class, ['label' => 'Add to favorites'])
            ->getForm();

        // check if form was submitted and handle data
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $book_id = $form->getData()['book__Id'];
            $user_id = $form->getData()['user__Id'];
            $book = $bookRepository->findBook($book_id);
            $user = $userRepository->findUser($user_id);
            $userBook->setBookId($book);
            $userBook->setUserId($user);
            $userBookRepository->save($userBook);
            return $this->redirectToRoute('home');
        }

        $this->stylesheets[] = 'bookinfo.css';
        return $this->render('bookInfo.html.twig', [
            'stylesheets'=> $this->stylesheets,
            'userBook_form'=>$form
        ]);
    }

    #[Route('/profile', name: 'profile')]
    public function profile(): Response
    {
        $this->stylesheets[] = 'profile.css';
        return $this->render('profile.html.twig', [
            'stylesheets'=> $this->stylesheets
        ]);
    }
}