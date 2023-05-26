<?php
/**
 * @fileoverview Controller for the Book info page, including API calls and rendering the page
 * @version 1.0
 */

/**
 * @author Ivan Badenhorst, Aymeric Baume
 * @since 2023-05-25.
 */

namespace App\Controller;

use App\Entity\UserBook;
use App\Form\BookAdd;
//use App\Form\BookRemove;
use App\Form\BookReview;
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
    public function bookInfo($bookId, BookRepository $bookRepository, UserBookRepository $userBookRepository): Response {
        $book = $bookRepository->findBook($bookId);

        $exists = $userBookRepository->check($bookId, 15);

        $form = $this->createForm(BookAdd::class);
        $view = $form->createView();

        if($exists){
            $view->children['add_to_favorites']->vars['label'] = 'Remove from favorites';
        }

        $form2 = $this->createForm(BookReview::class);


        $this->stylesheets[] = 'bookinfo.css';
        return $this->render('bookInfo.html.twig', [
            'stylesheets'=> $this->stylesheets,
            'form'=>$view,
            'form2'=>$form2,
            'book'=>$book
        ]);
    }

    #[Route('/add/{bookId}/{userId}', name: 'add')]
    public function add($bookId, $userId, BookRepository $bookRepository, UserRepository $userRepository, UserBookRepository $userBookRepository): Response
    {
        $userBook = new UserBook;

        $exists = $userBookRepository->check($bookId, $userId);

        if($exists){
            $userBook = $userBookRepository->findUserBook($bookId, $userId);
            $userBookRepository->remove($userBook, true);
        }
        else{
            $book = $bookRepository->findBook($bookId);
            $user = $userRepository->findUser($userId);
            $userBook->setBookId($book);
            $userBook->setUserId($user);

            $userBookRepository->save($userBook, true);

        }

        return new JsonResponse(true);
    }

    /**
     * Listens for API call for reviews
     *
     * @param $bookId -> id of the book for which the call wants reviews
     * @param $offset -> search offset for returned reviews
     * @param BookReviewsRepository $bookReviewsRepository
     * @return JsonResponse -> json containing reviews. Format:
     *                                    [{
     *                                          "comment":"",
     *                                          "score":0,
     *                                          "date_added":"",
     *                                          "display_name":""
     *                                      }, ...]
     */
    #[Route('/review/{bookId}/{offset}', name: 'review')]
    public function review($bookId, $offset, BookReviewsRepository $bookReviewsRepository): Response
    {
        $reviews = $bookReviewsRepository->getReviews($offset, 6,$bookId);
        return new JsonResponse($reviews);

    }

}