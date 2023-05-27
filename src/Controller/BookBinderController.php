<?php
/**
 * @fileoverview Controller for the home page
 * @version 2.2
 */

/**
 * @author Ivan Badenhorst, Emile Schockaert, Thomas Deseure
 * @since 2023-05-25.
 */

namespace App\Controller;

use App\Form\BookSearch;
use App\Form\NextPageControl;
use App\Repository\BookRepository;
use App\Repository\GenreRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\Preferences;


class BookBinderController extends AbstractController
{
    private array $stylesheets;

    public function __construct(private ManagerRegistry $doctrine)
    {
        $this->stylesheets[] = 'base.css';
    }

    /**
     * Generates the home page. All genres from the database are displayed along with a random list of 40 books
     *
     * @param Request $request
     * @param BookRepository $bookRepository
     * @param GenreRepository $genreRepository
     * @param RequestStack $requestStack
     * @return Response -> home page of the website
     * @throws \Doctrine\DBAL\Exception
     */
    #[Route('/', name: 'home')]
    public function home(BookRepository $bookRepository, GenreRepository $genreRepository, RequestStack $requestStack): Response
    {
        $logged = $this->checkSession($requestStack);
        //gets a list of all genres as string
        $genres_qry = $genreRepository->getGenre();
        $genres = [];
        foreach ($genres_qry as $genre){

            $genres[] = $genre['genre'];
        }

        //creates a form used as a search bar
        $form = $this->createForm(BookSearch::class);
        //create form for page control
        $pageControl = $this->createForm(NextPageControl::class);
        $this->stylesheets[] = 'main.css';

        //gets a list of 40 random books
        $books = $bookRepository->findLimitedRecords(40);
        $favourites = $bookRepository->findFavourites(4);

        return $this->render('main.html.twig', [
            'genres'=> $genres,
            'form' => $form->createView(),
            'stylesheets'=> $this->stylesheets,
            'books'=>$books,
            'pageControl'=>$pageControl->createView(),
            'favourites'=>$favourites,
            'logged' => $logged
        ]);
    }


    /**
     * When clicking on a book, the user is redirected to the book info page
     * This display more information about the book as well as the option to add it to the reading list
     * and to add a review or read reviews
     *
     * @param RequestStack $requestStack
     * @return Response
     */
    #[Route('/book-info', name: 'bookinfo')]
    public function infoBook(RequestStack $requestStack): Response
    {
        $logged = $this->checkSession($requestStack);
        $this->stylesheets[] = 'bookinfo.css';
        return $this->render('bookInfo.html.twig', [
            'stylesheets'=> $this->stylesheets,
            'logged' => $logged
        ]);
    }


    /**
     * Generates a book list which the user has added to their favorites
     *
     * @param RequestStack $requestStack
     * @return Response
     */
    #[Route('/read-list', name: 'readlist')]
    public function readlist(RequestStack $requestStack): Response
    {
        if($this->checkSession($requestStack)==false){
            return $this->redirectToRoute('login');
        }
        $session = $requestStack->getSession();
        $readingList = new \App\backend\ReadingList($this->doctrine->getManager());
        $list = $readingList->getReadingList($session->get('email'));
        $this->stylesheets[] = 'readingList.css';
        return $this->render('readingList.html.twig', [
            'stylesheets'=> $this->stylesheets,
            'list' => $list
        ]);
    }


    /**
     * Webpage with information about the website
     *
     * @param RequestStack $requestStack
     * @return Response
     */
    #[Route('/about', name: 'about')]
    public function about(RequestStack $requestStack): Response
    {
        $logged = $this->checkSession($requestStack);

        $this->stylesheets[] = 'readingList.css';
        $this->stylesheets[] = 'about.css';
        return $this->render('about.html.twig', [
            'stylesheets'=> $this->stylesheets,
            'logged'=>$logged
        ]);
    }


    /**
     * Webpage with contact information
     *
     * @param RequestStack $requestStack
     * @return Response
     */
    #[Route('/Contact', name: 'contact')]
    public function contact(RequestStack $requestStack){
        $this->stylesheets[] = 'contact.css';
        $logged = $this->checkSession($requestStack);
        return $this->render('contact.html.twig', [
            'stylesheets'=> $this->stylesheets,
            'logged' => $logged
        ]);
    }


    /**
     * redirect for pages that are still under construction
     *
     * @param RequestStack $requestStack
     * @return Response
     */
    #[Route('/underconstr', name: 'underconstr')]
    public function underconstr(RequestStack $requestStack){
        $logged = $this->checkSession($requestStack);
        return $this->render('underconstr.html.twig', [
            'logged' => $logged,
            'stylesheets'=> $this->stylesheets
        ]);
    }


    /**
     * returns true if the user is logged in
     *
     * @param RequestStack $requestStack
     * @return bool
     */
    private function checkSession(RequestStack $requestStack): bool
    {
        $session = $requestStack->getSession();
        $auth = new \App\backend\auth($this->doctrine->getManager());
        return($auth->isLogged($session));
    }



}