<?php
/**
 * @fileoverview Controller for API calls used for search and filter functionality on the home page -> see /src/js/search.js
 * @version 1.1
 */

/**
 * @author Ivan Badenhorst
 * @since 2023-05-24.
 */

namespace App\Controller;

use App\Repository\BookRepository;
use Doctrine\DBAL\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class SearchController extends AbstractController
{

    /**
     * Listens for API call, requesting book search
     *
     * @param $title -> search term used for finding books
     * @param $genres -> list of genres used to filter books
     * @param BookRepository $bookRepository
     * @return JsonResponse -> containing all book information from the search result
     * @throws Exception
     */
    #[Route('/search/{title}/{genres}/{offset}', name: 'search')]
    public function search($title, $genres, $offset, BookRepository $bookRepository): Response
    {
        $products = $bookRepository->searchOnTitle(41, $title, explode(",", $genres), $offset);
        return new JsonResponse($products);
    }

}