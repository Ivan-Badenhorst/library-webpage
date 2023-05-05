<?php

namespace App\backend;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\File;

class auth
{
    private \App\Repository\UserRepository $UserRepository;
    private EntityManagerInterface $entityManager;

    function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->UserRepository = $this->entityManager->getRepository(User::class);

    }



    public function login(String $email, String $password)
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

            return  "success";
        } else {
            $user->setLoginTries($user->getLoginTries() + 1);

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            return "password";
        }

    }
    public function register(String $email, String $password,String $name, String $surname, String $displayname, \DateTime $DOB, String $street, int $postalCode, String $city, File $profilePicture)
    {
        if($this->UserRepository->findOneBy(['email' => $email]) != null){
            return "email";
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

}
