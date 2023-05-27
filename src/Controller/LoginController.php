<?php
/**
 * @fileoverview php class loginController:  every route that has to do with login / register is described here
 * @version 1.0.0
 */

/**
 * @author Thomas Deseure
 * @since 2023-05-25.

 */


namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Persistence\ManagerRegistry;
use App\Form\register;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Session\SessionInterface;




class LoginController extends AbstractController
{

    private SessionInterface $session;
    private array $stylesheets;

    public function __construct(private ManagerRegistry $doctrine)
    {
        $this->stylesheets[] = 'login_register.css';
    }

    /**
     * register function:
     * makes a form, when the form is filled in and valid, data is sent to the auth class to be processed.
     * Depending on the status, the register was either succesfull in which case the user is redirected to the home page or unsuccesfull
     * in which case an error message is returned.
     *
     * @param Request $request - necessary for from
     * @param RequestStack $requestStack - necessary to get session
     * @return Response - render page
     */
    #[Route('/register', name: 'register')]
    public function register(Request $request, RequestStack $requestStack): Response
    {   //get session
        $this->session = $requestStack->getSession();
        //create form
        $form = $this->createForm(register::class);
        $form->handleRequest($request);
        //add stylesheet
        $this->stylesheets[] = 'login_register.css';

        //if form is submitted and valid
        if ($form->isSubmitted() && $form->isValid()) {
            //create auth object and give it the entity manager
            $auth = new \App\backend\auth($this->doctrine->getManager());
            //get profile picture and set it to default if none is given
            $profilePicture = $form->getData()['profilePicture'];
            if ($profilePicture == null) {
                $profilePicture = new File('../public/img/defaultProfilePicture.png');
            }
            else if($profilePicture->getSize()>2000000){
                return $this->render('register.html.twig', [
                    'stylesheets'=> $this->stylesheets,
                    'form' => $form->createView(),
                    'error' => "Profile picture is too big, max size is 2MB",
                    'logged' => false
                ]);
            }
            else if($profilePicture->getMimeType()!="image/jpeg"&&$profilePicture->getMimeType()!="image/png"&&$profilePicture->getMimeType()!="image/jpg"){
                return $this->render('register.html.twig', [
                    'stylesheets'=> $this->stylesheets,
                    'form' => $form->createView(),
                    'error' => "Profile picture is not a jpeg or png file",
                    'logged' => false
                ]);
            }
            if (gettype($form->getData()['postalCode'])!=="integer"){$postalCode = intval($form->getData()['postalCode']);}
            else {$postalCode = $form->getData()['postalCode'];}
            //send data to auth object
            $result = $auth->register($form->getData()['email'], $form->getData()['password'], $form->getData()['name'], $form->getData()['surname'], $form->getData()['displayname'], $form->getData()['DateOfBirth'], $form->getData()['street'], $postalCode, $form->getData()['city'], $profilePicture);

            //check result and return error or success

            if ($result == "success") {
                $auth->login($form->getData()['email'], $form->getData()['password'], $this->session);
                return $this->redirectToRoute('home');
            }

            //display error message
            else {
                return $this->render('register.html.twig', [
                    'stylesheets'=> $this->stylesheets,
                    'form' => $form->createView(),
                    'error' => $result,
                    'logged' => false
                ]);
            }
        }



        return $this->render('register.html.twig', [
            'form' => $form->createView(),
            'stylesheets'=> $this->stylesheets,
            'logged' => false
        ]);
    }

    /**
     * login function:
     * makes a form, when the form is filled in and valid, data is sent to the auth class to be processed.
     * Depending on the status, the login was either succesfull in which case the user is redirected to the home page or unsuccesfull
     * in which case an error message is returned.
     *
     * @param Request $request - necessary for from
     * @param RequestStack $requestStack - necessary to get session
     * @return Response - render page
     */
    #[Route('/login', name: 'login')]
    public function login(Request $request, RequestStack $requestStack): Response
    {   //get session
        $this->session = $requestStack->getSession();
        //get entity manager (list of all entities in the database
        $auth = new \App\backend\auth($this->doctrine->getManager());

        //check if user is already logged in, if they are redirect them to the home page
        //this is not necessary as we wont redirect them to the login page if they are already logged in
        //but its extra redundancy
        if($this->checkSession()){
            return $this->redirectToRoute('home');
        }

        //create the login form
        $form = $this->createForm(\App\Form\login::class);
        $form->handleRequest($request);

        //add the login css
        $this->stylesheets[] = 'login_register.css';


        //if the form is submitted and valid
        if ($form->isSubmitted() && $form->isValid()) {

            //send data to the auth class to check if the user exists
            $result = $auth->login($form->getData()['email'], $form->getData()['password'], $this->session);

            if ($result == "success") {
                return $this->redirectToRoute('home');
            }

            //display error message
            else {
                return $this->render('login.html.twig', [
                    'stylesheets'=> $this->stylesheets,
                    'form' => $form->createView(),
                    'error' => $result,
                    'logged' => false
                ]);
            }
        }


        return $this->render('login.html.twig', [
            'form' => $form->createView(),
            'stylesheets'=> $this->stylesheets,
            'logged' => false
        ]);

    }

    private \App\Repository\UserRepository $UserRepository;
    private EntityManagerInterface $entityManager;

    /**
     * example of how to display a profile picture
     * this function will display the profile picture of the user with the email "newUser@email.com"
     * the rest of the code to display the blob can be found in templates\displayBlob.html.twig
     * if the user does not have a profile picture it will display a default image, this image is the
     * profile picture of newUser@email.com
     *
     * @return Response - render page
     */
    #[Route('/image', name: 'image')]



    public function image()
    {
        $auth = new \App\backend\auth($this->doctrine->getManager());
        $imageData = $auth->getProfilePicture("Shell.Lutero@email.com");
        $imageBase64 = base64_encode($imageData);
        return $this->render('displayBlob.html.twig', [
            'image_data' => $imageBase64,
            'stylesheets'=> $this->stylesheets
        ]);
    }

    /**
     * You can use this function to check if a user is logged in,
     * the function returns true if the user is logged in and false
     * if the user is not logged in. You will still have to redirect
     * them to the login page if they are not logged in.
     *
     * @return bool - if logged in => true, else => false
     */
    private function checkSession(): bool
    {
        $auth = new \App\backend\auth($this->doctrine->getManager());
        return($auth->isLogged($this->session));
    }


    /**
     * You can use this function to log a user out.
     * Redirect to this route to log a user out, it automatically redirects to login
     *
     * @return Response - render login
     */
    #[Route('/logout', name: 'logout')]
    public function logout(RequestStack $requestStack): Response
    {
        $this->session = $requestStack->getSession();
        $this->session->set('email', "");
        $this->session->set('password', "");
        return $this->redirectToRoute('login');
    }



}

