<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BookBinderController extends AbstractController
{
    private array $stylesheets;

    public function __construct()
    {
        $this->stylesheets[] = 'main.css';
    }

    #[Route('/', name: 'home')]
    public function home(): Response
    {
        return $this->render('main.html.twig', [
            'stylesheets'=> $this->stylesheets
        ]);
    }
}