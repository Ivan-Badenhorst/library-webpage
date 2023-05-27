<?php
/**
 * @fileoverview Controller for the profile page
 * @version 1.0.0
 */

/**
 * @author Thomas Deseure
 * @since 2023-05-27.
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


class ProfileController extends AbstractController
{
    private array $stylesheets;

    public function __construct(private ManagerRegistry $doctrine)
    {
        $this->stylesheets[] = 'base.css';
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
            $firstname = $persInfo->get('firstName')->getData();
            $lastname = $persInfo->get('lastName')->getData();
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
                return $this->renderProfile($persInfo, $SecPriv, $message,"", $session, $auth);
            }
            if ($firstname == null) {
                $firstname = $auth->getFirstName($session->get('email'));
            }
            else if (strlen($firstname) >255 ){
                $message = "Firstname is too long";
                return $this->renderProfile($persInfo, $SecPriv, $message,"", $session, $auth);
            }
            if ($email == null) {
                $email = $session->get('email');
            }
            else if (strlen($email) >255 ){
                $message = "Email is too long";
                return $this->renderProfile($persInfo, $SecPriv, $message,"", $session, $auth);
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
                return $this->renderProfile($persInfo, $SecPriv, $message,"", $session, $auth);
            }
            if ($displayName == null) {
                $displayName = $auth->getDisplayName($session->get('email'));
            }
            else if (strlen($displayName) >255 ){
                $message = "Display name is too long";
                return $this->renderProfile($persInfo, $SecPriv, $message,"", $session, $auth);
            }
            if ($street == null) {
                $street = $auth->getStreet($session->get('email'));
            }
            else if(strlen($street) >255 ){
                $message = "Street is too long";
                return $this->renderProfile($persInfo, $SecPriv, $message,"", $session, $auth);
            }
            if ($postalCode == null) {
                $postalCode = $auth->getPostalCode($session->get('email'));
            }
            else if (strlen($postalCode) >10 ){
                $message = "Postal code is too long";
                return $this->renderProfile($persInfo, $SecPriv, $message,"", $session, $auth);
            }
            else if (gettype($postalCode)=="integer"){}
            else {$postalCode = intval($postalCode);}
            if ($city == null) {
                $city = $auth->getCity($session->get('email'));
            }
            else if (strlen($city) >255 ){
                $message = "City is too long";
                return $this->renderProfile($persInfo, $SecPriv, $message,"", $session, $auth);
            }
            if ($profilePic == null) {
                $auth->updatePersonalInfo($session->get('email'), $email, $firstname, $lastname, $displayName, $bio, $street, $postalCode, $city, $DOB);
            }
            else if($profilePic->guessExtension() != "jpg"&&$profilePic->guessExtension() != "png"&&$profilePic->guessExtension() != "jpeg"){
                $message = "Profile picture must be a jpeg or png file";
                return $this->renderProfile($persInfo, $SecPriv, $message,"", $session, $auth);
            }
            else if($profilePic->getSize() > 2000000){
                $message = "Profile picture must be smaller than 2MB";
                return $this->renderProfile($persInfo, $SecPriv, $message,"", $session, $auth);
            }
            else{
                $auth->updatePersonalInfoWPic($session->get('email'), $email, $firstname, $lastname, $displayName, $bio, $street, $postalCode, $city, $DOB, $profilePic);
            }
            $session->set('email', $email);
        }

        if ($SecPriv->isSubmitted() && $SecPriv->isValid()) {
            $newPassword = $SecPriv->get('password')->getData();
            if (strlen($newPassword) > 255) {
                $message = "Password is too long";
                return $this->renderProfile($persInfo, $SecPriv,"", $message, $session, $auth);
            }
            if (strlen($newPassword) < 8) {
                $message = "Password is too short";
                return $this->renderProfile($persInfo, $SecPriv,"", $message, $session, $auth);
            }
            $auth->updatePassword($session->get('email'), $newPassword);
        }

        return $this->renderProfile($persInfo, $SecPriv, "","", $session, $auth);
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
     * @param string $message2
     * @param \Symfony\Component\HttpFoundation\Session\SessionInterface $session
     * @param \App\backend\auth $auth
     * @return Response
     */
    public function renderProfile(\Symfony\Component\Form\FormInterface $persInfo, \Symfony\Component\Form\FormInterface $SecPriv, string $message,string $message2, \Symfony\Component\HttpFoundation\Session\SessionInterface $session, \App\backend\auth $auth): Response
    {
        return $this->render('profile.html.twig', [
            'stylesheets' => $this->stylesheets,
            'personalInfo' => $persInfo->createView(),
            'securityPrivacy' => $SecPriv->createView(),
            'message' => $message,
            'message2' => $message2,
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