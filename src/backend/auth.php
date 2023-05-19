<?php

namespace App\backend;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class auth
{
    private \App\Repository\UserRepository $UserRepository;
    private EntityManagerInterface $entityManager;

    function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->UserRepository = $this->entityManager->getRepository(User::class);

    }


//login user
//this function should not be called directly, but rather through the login function in the loginController
    public function login(String $email, String $password, SessionInterface $session)
    {
        $user = $this->UserRepository->findOneBy(['email' => $email]);

        if ($user == null) {
            return "email";
        }
        $userPassword = $user->getPassword();
        if ($user->getLoginTries() >= 5) {
            return "tries";
        }
        if (password_verify($password, $userPassword)) {
            $user->setLoginTries(0);

            $this->entityManager->persist($user);
            $this->entityManager->flush();
            $session->set('email', $email);
            $session->set('password', $password);
            //echo($session->get('email'));
            return  "success";
        } else {
            $user->setLoginTries($user->getLoginTries() + 1);

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            return "password";
        }

    }
    //register user
    //this function should not be called directly, but rather through the register function in the loginController
    public function register(String $email, String $password,String $name, String $surname, String $displayname, \DateTime $DOB, String $street, int $postalCode, String $city, File $profilePicture)
    {
        if($this->UserRepository->findOneBy(['email' => $email]) != null){
            return "email";
        }
        if(strlen($email) > 255){
            return "email too long";
        }
        if(strlen($name) > 255){
            return "name too long";
        }
        if(strlen($surname) > 255){
            return "surname too long";
        }
        if(strlen($displayname) > 255){
            return "displayname too long";
        }
        if(strlen($street) > 255){
            return "street too long";
        }
        if(strlen($city) > 255){
            return "city too long";
        }
        if(strlen($postalCode) > 10){
            return "postalCode too long";
        }
        if(strlen($password) > 255){
            return "password too long";
        }
        if(strlen($password) < 8){
            return "password too short";
        }

        $user = new User();
        $user->setEmail($email);
        $user->setPassword(password_hash($password, PASSWORD_DEFAULT));
        $user->setFirstName($name);
        $user->setLastName($surname);
        $user->setDisplayName($displayname);
        $user->setDateOfBirth($DOB);
        $user->setStreet($street);
        $user->setPostalCode($postalCode);
        $user->setCity($city);
        $user->setProfilePicture($profilePicture);
        $user->setLoginTries(0);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        return "success";
    }

    public function getProfilePicture(String $email)
    {
        $user = $this->UserRepository->findOneBy(['email' => $email]);
        return $user->getProfilePicture();
    }

    public function getDisplayName(String $email)
    {
        $user = $this->UserRepository->findOneBy(['email' => $email]);
        return $user->getDisplayName();
    }
    public function getFirstName(String $email)
    {
        $user = $this->UserRepository->findOneBy(['email' => $email]);
        return $user->getFirstName();
    }
    public function getLastName(String $email)
    {
        $user = $this->UserRepository->findOneBy(['email' => $email]);
        return $user->getLastName();
    }
    public function getStreet(String $email)
    {
        $user = $this->UserRepository->findOneBy(['email' => $email]);
        return $user->getStreet();
    }
    public function getPostalCode(String $email)
    {
        $user = $this->UserRepository->findOneBy(['email' => $email]);
        return $user->getPostalCode();
    }
    public function getCity(String $email)
    {
        $user = $this->UserRepository->findOneBy(['email' => $email]);
        return $user->getCity();
    }
    public function getDOB(String $email)
    {
        $user = $this->UserRepository->findOneBy(['email' => $email]);
        return $user->getDateOfBirth();
    }

    //check if user is logged in
    //this function should not be called directly, but rather through the isLogged function in the loginController
    public function isLogged(SessionInterface $session)
    {
        $user = $this->UserRepository->findOneBy(['email' => $session->get('email')]);

        if($session->get('email') == ""){
            header("Location: /login");
            return false;
        }

        if(password_verify($session->get('password'),$user->getPassword())){
            return true;
        }
        header("Location: /login");
        return false;
    }

    //logout user
    //this function should not be called directly, but rather through the logout function in the loginController
    public function logout(SessionInterface $session)
    {
        $session->set('email', "");
        $session->set('password', "");
        header("Location: /login");
    }




}
