<?php
/**
 * @fileoverview Controller for the home page
 * @version 2.2
 */

/**
 * @author Ivan Badenhorst, Emile Schockaert, Thomas Deseure
 * @since 2023-04-25.
 */

namespace App\Controller;

use App\Form\BookSearch;
use App\Form\NextPageControl;
use App\Repository\BookRepository;
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
use Symfony\Component\HttpFoundation\File\File;


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
        $SecPriv = $this->createForm(SecurityPrivacy::class);

        $persInfo->handleRequest($request);
        $SecPriv->handleRequest($request);

        if ($persInfo->isSubmitted() && $persInfo->isValid()) {
            $lastname = $persInfo->get('firstName')->getData();
            $surname = $persInfo->get('lastName')->getData();
            $displayName = $persInfo->get('displayName')->getData();
            $bio = $persInfo->get('bio')->getData();
            $street = $persInfo->get('street')->getData();
            $postalCode = $persInfo->get('postalCode')->getData();
            $city = $persInfo->get('city')->getData();
            $DOB = $persInfo->get('dateOfBirth')->getData();
            $profilePic = $persInfo->getData()['profilePicture'];
            $email = $persInfo->get('email')->getData();
            if ($lastname == null) {
                $lastname = $auth->getLastName($session->get('email'));
            }
            else if (strlen($lastname) >255 ){
                $message = "Last name is too long";
                return $this->renderProfile($persInfo, $SecPriv, $message, $session, $auth);
            }
            if ($surname == null) {
                $surname = $auth->getFirstName($session->get('email'));
            }
            else if (strlen($surname) >255 ){
                $message = "Surname is too long";
                return $this->renderProfile($persInfo, $SecPriv, $message, $session, $auth);
            }
            if ($email == null) {
                $email = $session->get('email');
            }
            else if (strlen($email) >255 ){
                $message = "Email is too long";
                return $this->renderProfile($persInfo, $SecPriv, $message, $session, $auth);
            }
            if ($DOB == null) {
                $DOB = $auth->getDOB($session->get('email'));
            }
            if ($bio == null) {
                $bio = $auth->getBio($session->get('email'));
                if ($bio == null) {
                    $bio = "";
                }
            }
            else if (strlen($bio) >1000 ){
                $message = "Bio is too long";
                return $this->renderProfile2($persInfo, $SecPriv, $message, $session, $auth);
            }
            if ($displayName == null) {
                $displayName = $auth->getDisplayName($session->get('email'));
            }
            else if (strlen($displayName) >255 ){
                $message = "Display name is too long";
                return $this->renderProfile2($persInfo, $SecPriv, $message, $session, $auth);
            }
            if ($street == null) {
                $street = $auth->getStreet($session->get('email'));
            }
            else if(strlen($street) >255 ){
                $message = "Street is too long";
                return $this->renderProfile2($persInfo, $SecPriv, $message, $session, $auth);
            }
            if ($postalCode == null) {
                $postalCode = $auth->getPostalCode($session->get('email'));
            }
            else if (strlen($postalCode) >10 ){
                $message = "Postal code is too long";
                return $this->renderProfile2($persInfo, $SecPriv, $message, $session, $auth);
            }
            else if (gettype($postalCode)=="integer"){}
            else {$postalCode = intval($postalCode);}
            if ($city == null) {
                $city = $auth->getCity($session->get('email'));
            }
            else if (strlen($city) >255 ){
                $message = "City is too long";
                return $this->renderProfile2($persInfo, $SecPriv, $message, $session, $auth);
            }
            if ($profilePic == null) {
                $auth->updatePersonalInfo($session->get('email'), $email, $lastname, $surname, $displayName, $bio, $street, $postalCode, $city, $DOB);
            }
            else if($profilePic->guessExtension() != "jpg"){
                $message = "Profile picture must be a jpg";
                return $this->renderProfile2($persInfo, $SecPriv, $message, $session, $auth);
            }
            else if($profilePic->getSize() > 2000000){
                $message = "Profile picture must be smaller than 2MB";
                return $this->renderProfile2($persInfo, $SecPriv, $message, $session, $auth);
            }
            else{
                    $auth->updatePersonalInfoWPic($session->get('email'), $email, $lastname, $surname, $displayName, $bio, $street, $postalCode, $city, $DOB, $profilePic);
                }
            $session->set('email', $email);
            }

        if ($SecPriv->isSubmitted() && $SecPriv->isValid()) {
            $newPassword = $SecPriv->get('password')->getData();
            if (strlen($newPassword) > 255) {
                $message = "Password is too long";
                return $this->renderProfile3($persInfo, $SecPriv, $message, $session, $auth);
            }
            if (strlen($newPassword) < 8) {
                $message = "Password is too short";
                return $this->renderProfile3($persInfo, $SecPriv, $message, $session, $auth);
            }
            $auth->updatePassword($session->get('email'), $newPassword);
        }

        return $this->render('profile.html.twig', [
            'personalInfo' => $persInfo->createView(),
            'securityPrivacy' => $SecPriv->createView(),
            'stylesheets'=> $this->stylesheets,
            'logged' => true,
            'message' => "",
            'message2' => "",
            'email' => $session->get('email'),
            'displayName' => $auth->getDisplayName($session->get('email')),
            'bio' => $auth->getBio($session->get('email')),
            'firstName' => $auth->getFirstName($session->get('email')),
            'lastName' => $auth->getLastName($session->get('email')),
            'street' => $auth->getStreet($session->get('email')),
            'postalCode' => $auth->getPostalCode($session->get('email')),
            'city' => $auth->getCity($session->get('email')),
            'DOB' => $auth->getDOB($session->get('email'))->format('Y-m-d'),
            'profilePicture' => base64_encode($auth->getProfilePicture($session->get('email')))
        ]);
    }

    #[Route('/Contact', name: 'contact')]
    public function contact(RequestStack $requestStack){
        $this->stylesheets[] = 'contact.css';
        $logged = $this->checkSession($requestStack);
        return $this->render('contact.html.twig', [
            'stylesheets'=> $this->stylesheets,
            'logged' => $logged
        ]);
    }

    #[Route('/underconstr', name: 'underconstr')]
    public function underconstr(RequestStack $requestStack){
        $logged = $this->checkSession($requestStack);
        return $this->render('underconstr.html.twig', [
            'logged' => $logged,
            'stylesheets'=> $this->stylesheets
        ]);
    }

    private function checkSession(RequestStack $requestStack): bool
    {
        $session = $requestStack->getSession();
        $auth = new \App\backend\auth($this->doctrine->getManager());
        return($auth->isLogged($session));
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $persInfo
     * @param \Symfony\Component\Form\FormInterface $SecPriv
     * @param string $message
     * @param \Symfony\Component\HttpFoundation\Session\SessionInterface $session
     * @param \App\backend\auth $auth
     * @return Response
     */
    public function renderProfile(\Symfony\Component\Form\FormInterface $persInfo, \Symfony\Component\Form\FormInterface $SecPriv, string $message, \Symfony\Component\HttpFoundation\Session\SessionInterface $session, \App\backend\auth $auth): Response
    {
        return $this->render('profile.html.twig', [
            'stylesheets' => $this->stylesheets,
            'personalInfo' => $persInfo->createView(),
            'securityPrivacy' => $SecPriv->createView(),
            'message' => $message,
            'message2' => null,
            'logged' => true,
            'email' => $session->get('email'),
            'displayName' => $auth->getDisplayName($session->get('email')),
            'bio' => $auth->getBio($session->get('email')),
            'firstName' => $auth->getFirstName($session->get('email')),
            'lastName' => $auth->getLastName($session->get('email')),
            'street' => $auth->getStreet($session->get('email')),
            'postalCode' => $auth->getPostalCode($session->get('email')),
            'city' => $auth->getCity($session->get('email')),
            'DOB' => $auth->getDOB($session->get('email'))->format('Y-m-d'),
            'profilePicture' => base64_encode($auth->getProfilePicture($session->get('email')))
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $persInfo
     * @param \Symfony\Component\Form\FormInterface $SecPriv
     * @param string $message
     * @param \Symfony\Component\HttpFoundation\Session\SessionInterface $session
     * @param \App\backend\auth $auth
     * @return Response
     */
    public function renderProfile2(\Symfony\Component\Form\FormInterface $persInfo, \Symfony\Component\Form\FormInterface $SecPriv, string $message, \Symfony\Component\HttpFoundation\Session\SessionInterface $session, \App\backend\auth $auth): Response
    {
        return $this->render('profile.html.twig', [
            'stylesheets' => $this->stylesheets,
            'personalInfo' => $persInfo->createView(),
            'securityPrivacy' => $SecPriv->createView(),
            'message' => $message,
            'message2' => "",
            'logged' => true,
            'email' => $session->get('email'),
            'displayName' => $auth->getDisplayName($session->get('email')),
            'bio' => $auth->getBio($session->get('email')),
            'firstName' => $auth->getFirstName($session->get('email')),
            'lastName' => $auth->getLastName($session->get('email')),
            'street' => $auth->getStreet($session->get('email')),
            'postalCode' => $auth->getPostalCode($session->get('email')),
            'city' => $auth->getCity($session->get('email')),
            'DOB' => $auth->getDOB($session->get('email'))->format('Y-m-d'),
            'profilePicture' => base64_encode($auth->getProfilePicture($session->get('email')))
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $persInfo
     * @param \Symfony\Component\Form\FormInterface $SecPriv
     * @param string $message
     * @param \Symfony\Component\HttpFoundation\Session\SessionInterface $session
     * @param \App\backend\auth $auth
     * @return Response
     */
    public function renderProfile3(\Symfony\Component\Form\FormInterface $persInfo, \Symfony\Component\Form\FormInterface $SecPriv, string $message, \Symfony\Component\HttpFoundation\Session\SessionInterface $session, \App\backend\auth $auth): Response
    {
        return $this->render('profile.html.twig', [
            'stylesheets' => $this->stylesheets,
            'personalInfo' => $persInfo->createView(),
            'securityPrivacy' => $SecPriv->createView(),
            'message' => "",
            'message2' => $message,
            'logged' => true,
            'email' => $session->get('email'),
            'displayName' => $auth->getDisplayName($session->get('email')),
            'bio' => $auth->getBio($session->get('email')),
            'firstName' => $auth->getFirstName($session->get('email')),
            'lastName' => $auth->getLastName($session->get('email')),
            'street' => $auth->getStreet($session->get('email')),
            'postalCode' => $auth->getPostalCode($session->get('email')),
            'city' => $auth->getCity($session->get('email')),
            'DOB' => $auth->getDOB($session->get('email'))->format('Y-m-d'),
            'profilePicture' => base64_encode($auth->getProfilePicture($session->get('email')))
        ]);
    }
}