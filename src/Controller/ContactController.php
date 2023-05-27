<?php
/**
 * @fileoverview Controller for the about page
 * @version 1.0
 */

/**
 * @author Thomas Deseure
 * @since 2023-05-27.
 */
namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    private array $stylesheets;

    public function __construct(private ManagerRegistry $doctrine)
    {
        $this->stylesheets[] = 'base.css';
    }

    /**
     * Webpage with contact information
     *
     * @param RequestStack $requestStack
     * @return Response
     */
    #[Route('/Contact', name: 'contact')]
    public function contact(RequestStack $requestStack){
        $this->stylesheets[] = 'contact.css';
        $logged = $this->checkSession($requestStack);
        return $this->render('contact.html.twig', [
            'stylesheets'=> $this->stylesheets,
            'logged' => $logged
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