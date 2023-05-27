<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AboutController extends AbstractController
{

    private array $stylesheets;

    public function __construct(private ManagerRegistry $doctrine)
    {
        $this->stylesheets[] = 'base.css';
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