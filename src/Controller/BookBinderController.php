<?php
/**
 * @fileoverview Controller for the home page
 * @version 2.2
 */

/**
 * @author Ivan Badenhorst, Emile Schockaert
 * @since 2023-04-25.
 */

namespace App\Controller;

use App\Form\BookSearch;
use App\Repository\BookRepository;
use App\Repository\GenreRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BookBinderController extends AbstractController
{
    private array $stylesheets;

    public function __construct(private ManagerRegistry $doctrine)
    {
        $this->stylesheets[] = 'base.css';
    }

    /**
     * Generates the home page. All genres from the database are displayed along with a random list of 40 books
     *
     * @param Request $request
     * @param BookRepository $bookRepository
     * @param GenreRepository $genreRepository
     * @return Response -> home page of the website
     * @throws \Doctrine\DBAL\Exception
     */
    #[Route('/', name: 'home')]
    public function home(Request $request, BookRepository $bookRepository, GenreRepository $genreRepository, RequestStack $requestStack): Response
    {
        $logged = $this->checkSession($requestStack);
        //gets a list of all genres as string
        $genres_qry = $genreRepository->getGenre();
        $genres = [];
        foreach ($genres_qry as $genre){

            $genres[] = $genre['genre'];
        }

        //creates a form used as a search bar
        $form = $this->createForm(BookSearch::class);
        $this->stylesheets[] = 'main.css';

        //gets a list of 40 random books
        $products = $bookRepository->findLimitedRecords(40);

        return $this->render('main.html.twig', [
            'genres'=> $genres,
            'form' => $form->createView(),
            'stylesheets'=> $this->stylesheets,
            'books'=>$products,
            'logged' => $logged
        ]);
    }

    #[Route('/book-info', name: 'bookinfo')]
    public function infoBook(RequestStack $requestStack): Response
    {
        $logged = $this->checkSession($requestStack);
        $this->stylesheets[] = 'bookinfo.css';
        return $this->render('bookInfo.html.twig', [
            'stylesheets'=> $this->stylesheets,
            'logged' => $logged
        ]);
    }

    #[Route('/profile', name: 'profile')]
    public function profile(RequestStack $requestStack): Response
    {
        if($this->checkSession($requestStack)==false){
            return $this->redirectToRoute('login');
        }
        $session = $requestStack->getSession();

        $auth = new \App\backend\auth($this->doctrine->getManager());
        $this->stylesheets[] = 'profile.css';
        return $this->render('profile.html.twig', [
            'stylesheets'=> $this->stylesheets,
            'logged' => true,
            'email' => $session->get('email'),
            'displayName' => $auth->getDisplayName($session->get('email')),
            'firstName' => $auth->getFirstName($session->get('email')),
            'lastName' => $auth->getLastName($session->get('email')),
            'street' => $auth->getStreet($session->get('email')),
            'postalCode' => $auth->getPostalCode($session->get('email')),
            'city' => $auth->getCity($session->get('email')),
            'DOB' => $auth->getDOB($session->get('email')),
            'profilePicture' => base64_encode($auth->getProfilePicture($session->get('email')))
        ]);
    }

    private function checkSession(RequestStack $requestStack): bool
    {
        $session = $requestStack->getSession();
        $auth = new \App\backend\auth($this->doctrine->getManager());
        return($auth->isLogged($session));
    }
}