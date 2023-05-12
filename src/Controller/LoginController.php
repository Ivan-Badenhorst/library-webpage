<?php


namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
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
        $form = $this->createForm(register::class);

        $form->handleRequest($request);
        $this->stylesheets[] = 'login_register.css';


        if ($form->isSubmitted() && $form->isValid()) {
            $auth = new \App\backend\auth($this->doctrine->getManager());
            $profilePicture = $form->getData()['profilePicture'];
            if ($profilePicture == null) {
                $profilePicture = new File('../public/img/defaultProfilePicture.png');
            }

            $result = $auth->register($form->getData()['email'], $form->getData()['password'], $form->getData()['name'], $form->getData()['surname'], $form->getData()['displayname'], $form->getData()['DateOfBirth'], $form->getData()['street'], $form->getData()['postalCode'], $form->getData()['city'], $profilePicture);
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
            else if ($result == "success") {
                return $this->render('register.html.twig', [
                    'stylesheets'=> $this->stylesheets,
                    'form' => $form->createView(),
                    'success' => "success"
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
    public function login(Request $request): Response
    {
        $this->session = $request->getSession();
        $auth = new \App\backend\auth($this->doctrine->getManager());
        if($auth->isLogged($this->session)){
            return $this->redirectToRoute('home');
        }


        $form = $this->createForm(\App\Form\login::class);

        $form->handleRequest($request);
        $this->stylesheets[] = 'login_register.css';



        if ($form->isSubmitted() && $form->isValid()) {

            $result = $auth->login($form->getData()['email'], $form->getData()['password'], $this->session);
            if ($result == "email") {
                return $this->render('login.html.twig', [
                    'stylesheets'=> $this->stylesheets,
                    'form' => $form->createView(),
                    'error' => "email not found"
                ]);
            }
            if ($result == "tries") {
                return $this->render('login.html.twig', [
                    'stylesheets'=> $this->stylesheets,
                    'form' => $form->createView(),
                    'error' => "too many tries"
                ]);
            }
            if ($result == "password") {
                return $this->render('login.html.twig', [
                    'stylesheets'=> $this->stylesheets,
                    'form' => $form->createView(),
                    'error' => "wrong password"
                ]);
            }
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
    public function image()
    {
        $auth = new \App\backend\auth($this->doctrine->getManager());
        $imageData = $auth->getProfilePicture("newUser@email.com");
        $imageBase64 = base64_encode($imageData);
        return $this->render('displayBlob.html.twig', [
            'image_data' => $imageBase64,
            'stylesheets'=> $this->stylesheets
        ]);
    }
}

