<?php

namespace App\backend;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;


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
        if ($userPassword == $password) {
            return  "success";
        } else {
            return "password";
        }

    }
    public function register(String $email, String $password,String $name, String $surname, String $displayname, String $DOB, String $street, int $postalCode, String $city)
    {
        if($this->UserRepository->findOneBy(['email' => $email]) != null){
            return "email";
        }
        $user = new User();
        $user->setEmail($email);
        $user->setPassword($password);
        $user->setFirstName($name);
        $user->setLastName($surname);
        $user->setDisplayName($displayname);
        $DTDOB = new \DateTime($DOB);
        $user->setDateOfBirth($DTDOB);
        $user->setStreet($street);
        $user->setPostalCode($postalCode);
        $user->setCity($city);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        return "success";
    }

}
