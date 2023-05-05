<?php


namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Persistence\ManagerRegistry;
use App\Form\register;
use Symfony\Component\HttpFoundation\File\File;



class BookBinderController extends AbstractController
{
    private array $stylesheets;

    public function __construct(private ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
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
            if ($result == "file size too big") {
                return $this->render('register.html.twig', [
                    'stylesheets'=> $this->stylesheets,
                    'form' => $form->createView(),
                    'error' => "file size too big"
                ]);
            }
            if ($result == "success") {
                return $this->render('register.html.twig', [
                    'stylesheets'=> $this->stylesheets,
                    'form' => $form->createView(),
                    'success' => "success"
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
        $form = $this->createForm(\App\Form\login::class);

        $form->handleRequest($request);
        $this->stylesheets[] = 'login_register.css';

        if ($form->isSubmitted() && $form->isValid()) {
            $auth = new \App\backend\auth($this->doctrine->getManager());
            $result = $auth->login($form->getData()['email'], $form->getData()['password']);
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

        return $this->render('register.html.twig', [
            'form' => $form->createView(),

            'stylesheets'=> $this->stylesheets
        ]);

    }
}
