<?php
/**
 * @fileoverview Controller for the Book info page, including API calls and rendering the page
 * @version 1.0
 */

/**
 * @author Ivan Badenhorst
 * @since 2023-05-25.
 */

namespace App\Controller;

use App\Entity\UserBook;
use App\Form\BookAdd;
use App\Form\BookReview;
use App\Repository\BookRepository;
use App\Repository\UserBookRepository;
use App\Repository\UserRepository;
use Doctrine\DBAL\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class BookInfoController extends AbstractController
{

    private array $stylesheets;

    public function __construct()
    {
        $this->stylesheets[] = 'base.css';
    }

    #[Route("/book-info/{bookId}", name: "book-info")]
    public function bookInfo($bookId, Request $request, BookRepository $bookRepository, UserRepository $userRepository, UserBookRepository $userBookRepository): Response {
        $book = $bookRepository->findBook($bookId);

        $form = $this->createForm(BookAdd::class);

        $form2 = $this->createForm(BookReview::class);

        $this->stylesheets[] = 'bookinfo.css';
        return $this->render('bookInfo.html.twig', [
            'stylesheets'=> $this->stylesheets,
            'form'=>$form,
            'form2'=>$form2,
            'book' => $book
        ]);
    }

    /*#[Route('/add/{bookId}/{userId}', name: 'add')]
    public function add($bookId, $userId, BookRepository $bookRepository): Response
    {
    }*/

    #[Route('/review/{bookId}/{offset}', name: 'review')]
    public function review($bookId, $offset, BookReviewsRepository $bookReviewsRepository): Response
    {
        //$reviews = $bookReviewsRepository->getReviews($offset, $title);
        return $this->render('reviews.html.twig');

    }

}