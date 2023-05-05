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

    #[Route('/book-info', name: 'bookinfo')]
    public function infoBook(): Response
    {
        $this->stylesheets[] = 'bookinfo.css';
        return $this->render('bookInfo.html.twig', [
            'stylesheets'=> $this->stylesheets
        ]);
    }
}