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

    #[Route('/login', name: 'login')]
    public function login(): Response
    {
        return $this->render('login.html.twig', [
            'stylesheets' => $this->stylesheets
        ]);
    }
    #[Route('/register', name: 'register')]
    public function register(): Response
    {
        return $this->render('register.html.twig', [
            'stylesheets' => $this->stylesheets
        ]);
    }
}
