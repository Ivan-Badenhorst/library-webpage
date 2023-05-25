<?php

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

    #[Route('/', name: 'home')]
    public function home(Request $request, BookRepository $bookRepository, GenreRepository $genreRepository): Response
    {
        $genres_qry = $genreRepository->getGenre();
        $genres = [];
        foreach ($genres_qry as $genre){

            $genres[] = $genre['genre'];
        }

        $form = $this->createForm(BookSearch::class);
        $this->stylesheets[] = 'main.css';

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