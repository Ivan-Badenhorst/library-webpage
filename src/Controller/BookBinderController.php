<?php

namespace App\Controller;

use App\Form\BookSearch;
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
    public function home(Request $request): Response
    {
        $form = $this->createForm(BookSearch::class);

        $form->handleRequest($request);
        $this->stylesheets[] = 'main.css';


        if ($form->isSubmitted() && $form->isValid()) {
            $searchTerm = $form->getData()['search_term'];

            // Do something with the search term

            return $this->render('main.html.twig', [
                'stylesheets'=> $this->stylesheets,
                'search_term'=>$searchTerm,
                'form' => $form->createView()
            ]);
        }



        return $this->render('main.html.twig', [
            'form' => $form->createView(),
            'stylesheets'=> $this->stylesheets
        ]);
    }
}