<?php

/**
 * @fileoverview php class for authentification:  all communication with User table happens trough here.
 * @version 1.0.0
 */

/**
 * @author Thomas Deseure
 * @since 2023-05-25.
 */

/**
 *  to call a function from this class, use the following code in your controller:
 *    //get entity manager (list of all entities in the database)
 *    $auth = new \App\backend\auth($this->doctrine->getManager());
 *    //call function
 *    $auth-><function>;
 *  you will also need to use this as constructor in your controller (you can add more variables or code if you need them)
 *    public function __construct(private ManagerRegistry $doctrine)
 *    {
 *        $this->stylesheets[] = 'main.css';
 *    }
 *  some functions use sessions, preferably call the functions already implemented in loginController.php but if needed you can also call them directly:
 *  You will need the requestStack, you can get it by putting it as a variable of your controller function, for example:
 *    public function login(RequestStack $RequestStack): Response{
 *        //$session = $RequestStack->getSession();
 *        //$auth = new \App\backend\auth($this->doctrine->getManager());
 *        //$auth->login($email, $password, $session);
 *        }
 */


namespace App\backend;
use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Session\SessionInterface;



class auth
{
    /**
     * @param \App\Repository\UserRepository $UserRepository - List of all users, gets assigned in the constructor
     * @param EntityManagerInterface $entityManager - List of all database entities, gets assigned in the constructor
     */
    private \App\Repository\UserRepository $UserRepository;
    private EntityManagerInterface $entityManager;

    function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->UserRepository = $this->entityManager->getRepository(User::class);

    }

    /**
     *  login user
     *  this function should not be called directly, but rather through the login function in the loginController
     *
     * @param String $email - email with which the user is trying to login
     * @param String $password - password with which the suer is trying to login
     * @param SessionInterface $session - session
     * @return string - message that reports status of login
     */
    public function login(String $email, String $password, SessionInterface $session)
    {
        $user = $this->UserRepository->findOneBy(['email' => $email]);

        if ($user == null) {
            return "there is no account registered with this email";
        }
        $userPassword = $user->getPassword();
        if ($user->getLoginTries() >= 5) {
            return "too many login attempts";
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

            return "wrong password, you have " . (5 - $user->getLoginTries()) . " tries left";
        }

    }

    /**
     * register user
     * this function should not be called directly, but rather through the register function in the loginController
     *
     * @param String $email - email with which the user is trying to register
     * @param String $password - password with which the suer is trying to register
     * @param String $name - register inputs
     * @param String $surname
     * @param String $displayname
     * @param \DateTime $DOB
     * @param String $street
     * @param int $postalCode
     * @param String $city
     * @param File $profilePicture
     * @return string - message that reports status of register
     */
    public function register(String $email, String $password,String $name, String $surname, String $displayname, \DateTime $DOB, String $street, int $postalCode, String $city, File $profilePicture)
    {
        if($this->UserRepository->findOneBy(['email' => $email]) != null){
            return "email already in use";
        }
        if(strlen($email) > 255){
            return "email too long, must be at most 255 characters long";
        }
        if(strlen($name) > 255){
            return "name too long, must be at most 255 characters long";
        }
        if(strlen($surname) > 255){
            return "surname too long, must be at most 255 characters long";
        }
        if(strlen($displayname) > 255){
            return "displayname too long, must be at most 255 characters long";
        }
        if(strlen($street) > 255){
            return "street too long, must be at most 255 characters long";
        }
        if(strlen($city) > 255){
            return "city too long, must be at most 255 characters long";
        }
        if(strlen($postalCode) > 10){
            return "postalCode too long, must be at most 10 characters long";
        }
        if(strlen($password) > 255){
            return "password too long, must be at most 255 characters long";
        }
        if(strlen($password) < 8){
            return "password too short, must be at least 8 characters long";
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

    /**
     * returns profilepicture for a certain email
     * if profilepicture == "null" (a String assigned to the $profilePicture variable in the User class
     * when there is no profile picture on the database for this user) a default profilePicture will be returned
     * the image still needs to be processed, to find out how to use this function, check the example in the loginController
     *
     * @param String $email - email
     * @return string|null - profile picture
     */
    public function getProfilePicture(String $email)
    {
        $user = $this->UserRepository->findOneBy(['email' => $email]);
        $profilePicture = $user->getProfilePicture();
        if ($profilePicture == "null") {
            $user = $this->UserRepository->findOneBy(['email' => 'newUser@email.com']);

            return $user->getProfilePicture();
            }
        return $profilePicture;
    }


    /**
     * following functions can be used to get user information, check the loginController for examples on how to call them
     * inputs are always email
     */
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

    /**
     * check if user is logged in
     * you can find an example of how to call this function in the checkSession function in the loginController
     *
     * @param SessionInterface $session - session
     * @return bool - if user logged in => true else => false
     */
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

    /**
     * logout user
     * you can find an example of how to call this function in the logout function in the loginController
     *
     * @param SessionInterface $session - session
     */
    public function logout(SessionInterface $session)
    {
        $session->set('email', "");
        $session->set('password', "");
        header("Location: /login");
    }




}
