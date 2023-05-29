<?php
/**
 * @fileoverview Controller for the home page
 * @version 2.3
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
use Doctrine\DBAL\Exception;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class BookBinderController extends AbstractController
{
    private array $stylesheets;

    public function __construct(private ManagerRegistry $doctrine)
    {
        $this->stylesheets[] = 'base.css';
        $this->stylesheets[] = 'main.css';
    }

    /**
     * Generates the home page. All genres from the database are displayed along with a random list of 40 books
     *
     * @param BookRepository $bookRepository
     * @param GenreRepository $genreRepository
     * @param RequestStack $requestStack
     * @return Response -> home page of the website
     * @throws Exception
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

        //gets a list of 40 random books -> first books in the database
        $books = $bookRepository->findLimitedRecords(40);
        //gets a list of 4 highest rated books
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
     * Listens for API call, requesting book search
     *
     * @param $title -> search term used for finding books
     * @param $genres -> list of genres used to filter books
     * @param $offset -> search offset
     * @param BookRepository $bookRepository
     * @return JsonResponse -> containing all book information from the search result
     * @throws Exception
     */
    #[Route('/search/{title}/{genres}/{offset}', name: 'search')]
    public function search($title, $genres, $offset, BookRepository $bookRepository): Response
    {

        $genres = str_replace('title_place_holder', '%', $genres);
        $genres = str_replace('this_is_a_space', ' ', $genres);
        $products = $bookRepository->searchOnTitle(41, $title, explode(",", $genres), $offset);
        return new JsonResponse($products);

    }

    /**
     * Used to check if a user is logged in -> uses session
     *
     * @param RequestStack $requestStack
     * @return bool -> true indicates the user is logged in and vice versa for false
     */
    private function checkSession(RequestStack $requestStack): bool
    {
        $session = $requestStack->getSession();
        $auth = new \App\backend\auth($this->doctrine->getManager());
        return($auth->isLogged($session));
    }





}