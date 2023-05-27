<?php
/**
 * @fileoverview Controller for the about page
 * @version 1.0
 */

/**
 * @author Ivan Badenhorst
 * @since 2023-05-27.
 */

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
     * Renders a page containing about information
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
     * @return bool -> true indicates the user is logged in and vice versa for false
     */
    private function checkSession(RequestStack $requestStack): bool
    {
        $session = $requestStack->getSession();
        $auth = new \App\backend\auth($this->doctrine->getManager());
        return($auth->isLogged($session));
    }

}