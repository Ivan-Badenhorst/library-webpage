<?php
/**
 * @fileoverview Controller for the home page
 * @version 2.2
 */

/**
 * @author Ivan Badenhorst, Emile Schockaert
 * @since 2023-04-25.
 */

namespace App\Controller;

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

    #[Route('/book-info', name: 'bookinfo')]
    public function infoBook(): Response
    {
        $this->stylesheets[] = 'bookinfo.css';
        return $this->render('bookInfo.html.twig', [
            'stylesheets'=> $this->stylesheets
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