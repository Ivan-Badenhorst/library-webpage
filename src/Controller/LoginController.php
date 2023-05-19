<?php


namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Persistence\ManagerRegistry;
use App\Form\register;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\Session\SessionInterface;




class LoginController extends AbstractController
{

    private SessionInterface $session;
    private array $stylesheets;

    public function __construct(private ManagerRegistry $doctrine)
    {
        $this->stylesheets[] = 'main.css';
    }


    #[Route('/register', name: 'register')]
    public function register(Request $request): Response
    {
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
            //send data to auth object
            $result = $auth->register($form->getData()['email'], $form->getData()['password'], $form->getData()['name'], $form->getData()['surname'], $form->getData()['displayname'], $form->getData()['DateOfBirth'], $form->getData()['street'], $form->getData()['postalCode'], $form->getData()['city'], $profilePicture);
            //check result and return error or success
            if ($result == "email") {
                return $this->render('register.html.twig', [
                    'stylesheets'=> $this->stylesheets,
                    'form' => $form->createView(),
                    'error' => "email already in use"
                ]);
            }
            else if ($result == "file size too big") {
                return $this->render('register.html.twig', [
                    'stylesheets'=> $this->stylesheets,
                    'form' => $form->createView(),
                    'error' => "file size too big"
                ]);
            }

            //TODO needs to be changed to redirect to home in the main branch
            else if ($result == "success") {
                return $this->render('register.html.twig', [
                    'stylesheets'=> $this->stylesheets,
                    'form' => $form->createView(),
                    'succes' => "success"

                ]);
            }
            else if ($result == "email too long") {
                return $this->render('register.html.twig', [
                    'stylesheets'=> $this->stylesheets,
                    'form' => $form->createView(),
                    'error' => "email too long"
                ]);
            }
            else if ($result == "name too long") {
                return $this->render('register.html.twig', [
                    'stylesheets'=> $this->stylesheets,
                    'form' => $form->createView(),
                    'error' => "name too long"
                ]);
            }
            else if ($result == "unknown error") {
                return $this->render('register.html.twig', [
                    'stylesheets'=> $this->stylesheets,
                    'form' => $form->createView(),
                    'error' => "unknown error"
                ]);
            }
            else if ($result == "email too long") {
                return $this->render('register.html.twig', [
                    'stylesheets'=> $this->stylesheets,
                    'form' => $form->createView(),
                    'error' => "email too long (needs to be at most 255 characters long)"
                ]);
            }
            else if ($result == "surname too long") {
                return $this->render('register.html.twig', [
                    'stylesheets'=> $this->stylesheets,
                    'form' => $form->createView(),
                    'error' => "surname too long (needs to be at most 255 characters long)"
                ]);
            }
            else if ($result == "displayname too long") {
                return $this->render('register.html.twig', [
                    'stylesheets'=> $this->stylesheets,
                    'form' => $form->createView(),
                    'error' => "displayname too long (needs to be at most 255 characters long)"
                ]);
            }
            else if ($result == "street too long") {
                return $this->render('register.html.twig', [
                    'stylesheets'=> $this->stylesheets,
                    'form' => $form->createView(),
                    'error' => "street too long (needs to be at most 255 characters long)"
                ]);
            }
            else if ($result == "city too long") {
                return $this->render('register.html.twig', [
                    'stylesheets'=> $this->stylesheets,
                    'form' => $form->createView(),
                    'error' => "city too long (needs to be at most 255 characters long)"
                ]);
            }
            else if ($result == "postalCode too long") {
                return $this->render('register.html.twig', [
                    'stylesheets'=> $this->stylesheets,
                    'form' => $form->createView(),
                    'error' => "postalCode too long (only 10 digits)"
                ]);
            }
            else if ($result == "password too long") {
                return $this->render('register.html.twig', [
                    'stylesheets'=> $this->stylesheets,
                    'form' => $form->createView(),
                    'error' => "password too long (needs to be at most 255 characters long)"
                ]);
            }
            else if ($result == "password too short") {
                return $this->render('register.html.twig', [
                    'stylesheets'=> $this->stylesheets,
                    'form' => $form->createView(),
                    'error' => "password too short (needs to be at least 8 characters long)"
                ]);
            }
            else {
                return $this->render('register.html.twig', [
                    'stylesheets'=> $this->stylesheets,
                    'form' => $form->createView(),
                    'error' => "unknown error"
                ]);
            }
        }



        return $this->render('register.html.twig', [
            'form' => $form->createView(),

            'stylesheets'=> $this->stylesheets
        ]);
    }


    #[Route('/login', name: 'login')]
    public function login(Request $request, RequestStack $requestStack): Response
    {//get session
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
            if ($result == "email") {
                return $this->render('login.html.twig', [
                    'stylesheets'=> $this->stylesheets,
                    'form' => $form->createView(),
                    'error' => "email not found"
                ]);
            }
            //if the user has tried to login too many times
            if ($result == "tries") {
                return $this->render('login.html.twig', [
                    'stylesheets'=> $this->stylesheets,
                    'form' => $form->createView(),
                    'error' => "too many tries"
                ]);
            }

            //if the user has entered the wrong password
            if ($result == "password") {
                return $this->render('login.html.twig', [
                    'stylesheets'=> $this->stylesheets,
                    'form' => $form->createView(),
                    'error' => "wrong password"
                ]);
            }

            //if the login was succesfull

            //TODO this code should be uncommented when it is integrated into the main branch
            //it cant be used untill then as this branch doesn't have the home page
//            if ($result == "success") {
//                return $this->redirectToRoute('home');
//            }
        }

        return $this->render('login.html.twig', [
            'form' => $form->createView(),

            'stylesheets'=> $this->stylesheets
        ]);

    }

    private \App\Repository\UserRepository $UserRepository;
    private EntityManagerInterface $entityManager;

    #[Route('/image', name: 'image')]


    //example of how to display a blob
    //this function will display the profile picture of the user with the email "newUser@email.com"
    //the rest of the code to display the blob can be found in templates\displayBlob.html.twig

    public function image()
    {
        $auth = new \App\backend\auth($this->doctrine->getManager());
        //$imageData = $auth->getProfilePicture("Selma.Anderea@email.com");
        $imageData = $auth->getProfilePicture("newUser@email.com");
        $imageBase64 = base64_encode($imageData);
        return $this->render('displayBlob.html.twig', [
            'image_data' => $imageBase64,
            'stylesheets'=> $this->stylesheets
        ]);
    }

//You can use this function to check if a user is logged in,
//the function returns true if the user is logged in and false
//if the user is not logged in. You will still have to redirect
//them to the login page if they are not logged in.
    public function checkSession(): bool
    {
        $auth = new \App\backend\auth($this->doctrine->getManager());
        return($auth->isLogged($this->session));
    }

    //You can use this function to log a user out. It does not
    //return anything and you will still need to redirect them to the login page.
    public function logout(): void
    {
        $auth = new \App\backend\auth($this->doctrine->getManager());
        $auth->logout($this->session);
    }



}

