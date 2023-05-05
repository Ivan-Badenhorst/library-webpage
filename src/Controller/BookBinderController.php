<?php


namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Persistence\ManagerRegistry;


class BookBinderController extends AbstractController
{
    private array $stylesheets;

    public function __construct(private ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
        $this->stylesheets[] = 'main.css';
    }

    #[Route('/login', name: 'login')]
    public function login(): Response
    {
        return $this->render('login.html.twig', [
            'stylesheets' => $this->stylesheets
        ]);
    }
    #[Route('/register', name: 'register')]
    public function register(): Response
    {
        return $this->render('register.html.twig', [
            'stylesheets' => $this->stylesheets
        ]);
    }
    #[Route('/login/handler', name: 'loginh')]
    public function loginh(): Response
    {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $auth = new \App\backend\auth($this->doctrine->getManager());
        return new Response($auth->login($email, $password));
    }
    #[Route('/register/handler', name: 'registerH')]
    public function registerH(): Response
    {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $name = $_POST['name'];
        $surname = $_POST['surname'];
        $displayname = $_POST['displayname'];
        $DOB = $_POST['dob'];
        $street = $_POST['street'];
        $postalCode = $_POST['postalCode'];
        $city = $_POST['city'];

        $auth = new \App\backend\auth($this->doctrine->getManager());

        return new Response($auth->register($email, $password, $name, $surname, $displayname, $DOB, $street, $postalCode, $city));
    }

}
