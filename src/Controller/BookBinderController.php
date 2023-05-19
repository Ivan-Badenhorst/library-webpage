<?php

namespace App\Controller;

use App\Form\BookSearch;
use App\Repository\BookRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;

class BookBinderController extends AbstractController
{
    private array $stylesheets;

    public function __construct()
    {
        $this->stylesheets[] = 'base.css';
    }

    #[Route('/', name: 'home')]
    public function home(Request $request, BookRepository $bookRepository): Response
    {
        $form = $this->createForm(BookSearch::class);

        $form->handleRequest($request);
        $this->stylesheets[] = 'main.css';


        if ($form->isSubmitted() && $form->isValid()) {
            $searchTerm = $form->getData()['search_term'];

            // Do something with the search term
            $products = $bookRepository->searchOnTitle(40, $searchTerm);

//            return $this->render('main.html.twig', [
//                'stylesheets'=> $this->stylesheets,
//                'search_term'=>$searchTerm,
//                'form' => $form->createView()
//            ]);
        }
        else{
            //get a list of all books to display
            //for now these books are random!
            $products = $bookRepository->findLimitedRecords(40);
        }




        return $this->render('main.html.twig', [
            'form' => $form->createView(),
            'stylesheets'=> $this->stylesheets,
            'books'=>$products
        ]);
    }
}