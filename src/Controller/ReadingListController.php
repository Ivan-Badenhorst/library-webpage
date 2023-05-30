<?php
/**
 * @fileoverview Controller for the reading list page
 * @version 1.1
 */

/**
 * @author Thomas Deseure
 * @since 2023-05-25.
 */

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReadingListController extends AbstractController
{

    private array $stylesheets;

    public function __construct(private ManagerRegistry $doctrine)
    {
        $this->stylesheets[] = 'base.css';
    }


    /**
     * Generates a book list which the user has added to their favorites
     *
     * @param RequestStack $requestStack
     * @return Response
     */
    #[Route('/read-list', name: 'readlist')]
    public function readlist(RequestStack $requestStack): Response
    {
        if($this->checkSession($requestStack)==false){
            return $this->redirectToRoute('login');
        }
        $session = $requestStack->getSession();
        $readingList = new \App\backend\ReadingList($this->doctrine->getManager());
        $list = $readingList->getReadingList($session->get('email'));
        $this->stylesheets[] = 'readingList.css';

        $logged = $this->checkSession($requestStack);

        return $this->render('readingList.html.twig', [
            'stylesheets'=> $this->stylesheets,
            'list' => $list,
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