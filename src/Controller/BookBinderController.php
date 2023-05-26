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
use App\Repository\UserBookRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Repository\GenreRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\PersonalInfo;
use App\Form\SecurityPrivacy;
use App\Form\Preferences;


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
        //create form for page control
        $pageControl = $this->createForm(NextPageControl::class);
        $this->stylesheets[] = 'main.css';

        //gets a list of 40 random books
        $books = $bookRepository->findLimitedRecords(40);
        $favourites = $bookRepository->findFavourites(4);

        return $this->render('main.html.twig', [
            'genres'=> $genres,
            'form' => $form->createView(),
            'stylesheets'=> $this->stylesheets,
            'books'=>$books,
            'pageControl'=>$pageControl->createView(),
            'favourites'=>$favourites,
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
        return $this->render('readingList.html.twig', [
            'stylesheets'=> $this->stylesheets,
            'list' => $list
        ]);
    }

    #[Route('/profile', name: 'profile')]
    public function profile(Request $request,RequestStack $requestStack): Response
    {
        if($this->checkSession($requestStack)==false){
            return $this->redirectToRoute('login');
        }
        $session = $requestStack->getSession();

        $auth = new \App\backend\auth($this->doctrine->getManager());
        $this->stylesheets[] = 'profile.css';

        $persInfo = $this->createForm(PersonalInfo::class);
        $pref = $this->createForm(Preferences::class);
        $SecPriv = $this->createForm(SecurityPrivacy::class);

        $persInfo->handleRequest($request);
        $pref->handleRequest($request);
        $SecPriv->handleRequest($request);

        if ($persInfo->isSubmitted() && $persInfo->isValid()) {
            // Handle form submission and process checkbox values

            // Access checkbox values
            $checkbox1Value = $persInfo->get('checkbox1')->getData();
            $checkbox2Value = $persInfo->get('checkbox2')->getData();
            $checkbox3Value = $persInfo->get('checkbox3')->getData();

            // ... Process the checkbox values as needed

            // Redirect or return a response
        }
        if ($pref->isSubmitted() && $pref->isValid()) {
            // Handle form submission and process checkbox values

            // Access checkbox values
            $checkbox1Value = $pref->get('checkbox1')->getData();
            $checkbox2Value = $pref->get('checkbox2')->getData();
            $checkbox3Value = $pref->get('checkbox3')->getData();

            // ... Process the checkbox values as needed

            // Redirect or return a response
        }
        if ($SecPriv->isSubmitted() && $SecPriv->isValid()) {
            // Handle form submission and process checkbox values

            // Access checkbox values
            $checkbox1Value = $SecPriv->get('checkbox1')->getData();
            $checkbox2Value = $SecPriv->get('checkbox2')->getData();
            $checkbox3Value = $SecPriv->get('checkbox3')->getData();

            // ... Process the checkbox values as needed

            // Redirect or return a response
        }

        return $this->render('profile.html.twig', [
            'personalInfo' => $persInfo->createView(),
            'preferences' => $pref->createView(),
            'securityPrivacy' => $SecPriv->createView(),
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