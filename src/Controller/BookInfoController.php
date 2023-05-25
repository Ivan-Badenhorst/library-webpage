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
use App\Repository\BookRepository;
use App\Repository\BookReviewsRepository;
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
        // create form
        // ref : https://symfony.com/doc/current/forms.html
        $userBook = new UserBook();
        $form = $this->createFormBuilder($userBook)
            ->add('save',SubmitType::class, ['label' => 'Add to favorites'])
            ->getForm();

        // check if form was submitted and handle data
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $userRepository->findUser(15);
            $userBook->setBookId($book);
            $userBook->setUserId($user);
            $userBookRepository->save($userBook, true);
            return $this->redirectToRoute('home');
        }

        $this->stylesheets[] = 'bookinfo.css';
        return $this->render('bookInfo.html.twig', [
            'stylesheets'=> $this->stylesheets,
            'userBook_form'=>$form,
            'book' => $book
        ]);
    }

    #[Route('/review/{bookId}/{offset}', name: 'review')]
    public function review($bookId, $offset, BookReviewsRepository $bookReviewsRepository): Response
    {
        //$reviews = $bookReviewsRepository->getReviews($offset, $title);
        return $this->render('reviews.html.twig');

    }

}