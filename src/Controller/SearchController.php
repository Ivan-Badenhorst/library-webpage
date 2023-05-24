<?php

namespace App\Controller;

use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class SearchController extends AbstractController
{

    //listens for ajax requests and returns a json
    #[Route('/search/{title}', name: 'search')]
    public function search($title, BookRepository $bookRepository): Response
    {
        $products = $bookRepository->searchOnTitle(40, $title);
        return new JsonResponse($products);

    }

}