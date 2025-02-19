<?php
/**
 * @fileoverview Controller for the Book info page, including API calls and rendering the page
 * @version 1.1
 */

/**
 * @author Ivan Badenhorst, Aymeric Baume
 * @since 2023-05-25.
 */

namespace App\Controller;

use App\backend\auth;
use App\Entity\BookReviews;
use App\Entity\UserBook;
use App\Form\BookAdd;
use App\Form\BookReview;
use App\Form\WriteReview;
use App\Repository\BookRepository;
use App\Repository\BookReviewsRepository;
use App\Repository\UserBookRepository;
use App\Repository\UserRepository;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class BookInfoController extends AbstractController
{

    private array $stylesheets;

    public function __construct(private ManagerRegistry $doctrine)
    {
        $this->stylesheets[] = 'base.css';
    }


    /**
     * Initializes the book info page after the div containing each book's information is clicked on.
     * Checks for whether the user has the book in their reading list and changes the state of the favorites form accordingly.
     * Also checks whether the user is logged in, allowing them to write reviews.
     *
     * @param $bookId -> id of the book that was clicked on, used to find the book and then fetch all its data.
     * @param BookRepository $bookRepository
     * @param UserBookRepository $userBookRepository
     * @param RequestStack $requestStack
     * @param EntityManagerInterface $entityManager
     * @return Response -> containing the book info page for the given book
     */
    #[Route("/book-info/{bookId}", name: "book-info")]
    public function bookInfo($bookId, BookRepository $bookRepository, UserBookRepository $userBookRepository,  RequestStack $requestStack, EntityManagerInterface $entityManager): Response {

        $logged = $this->checkSession($requestStack);
        $book = $bookRepository->findBook($bookId);
        $bookRating = $bookRepository->findBookRating($bookId);

        if($logged) {
            $email = $requestStack->getSession()->get('email');
            //get id from email
            $auth = new auth($entityManager);
            $userID = $auth->getID($email);

            $exists = $userBookRepository->check($bookId, $userID);
        }
        else{
            $exists = false;
        }


        //Form for adding/removing book from favourites
        $favoritesForm = $this->createForm(BookAdd::class);
        $view = $favoritesForm->createView();
        if($exists){
            $view->children['add_to_favorites']->vars['label'] = 'Remove from favorites';
        }

        //Form for viewing more reviews
        $viewReviewsForm = $this->createForm(BookReview::class);
        $view2 = $viewReviewsForm->createView();
        //Form for writing reviews
        $writeReviewForm = $this->createForm(WriteReview::class);
        $view3 = $writeReviewForm->createView();

        $this->stylesheets[] = 'bookinfo.css';
        return $this->render('bookInfo.html.twig', [
            'stylesheets'=> $this->stylesheets,
            'favoritesForm'=>$view,
            'viewReviewsForm'=>$view2,
            'writeReviewForm'=>$view3,
            'book'=>$book,
            'logged'=>$logged,
            'rating'=>$bookRating
        ]);
    }

    /**
     * Listens for API call and reacts by adding or removing the book from the reading list database.
     *
     * @param $bookId -> id of the book to add/remove from favourites
     * @param BookRepository $bookRepository
     * @param UserRepository $userRepository
     * @param UserBookRepository $userBookRepository
     * @param RequestStack $requestStack
     * @param EntityManagerInterface $entityManager
     * @return JsonResponse -> true since it doesnt need to display anything. Return value is mandatory. True indicates success
     */
    #[Route('/add/{bookId}', name: 'add')]
    public function add($bookId, BookRepository $bookRepository, UserRepository $userRepository, UserBookRepository $userBookRepository, RequestStack $requestStack, EntityManagerInterface $entityManager): Response
    {
        $userBook = new UserBook;

        $email = $requestStack->getSession()->get('email');
        //get id from email
        $auth = new auth($entityManager);
        $userID = $auth->getID($email);

        $exists = $userBookRepository->check($bookId, $userID);

        if($exists){
            $userBook = $userBookRepository->findUserBook($bookId, $userID);
            $userBookRepository->remove($userBook, true);
        }
        else{
            $book = $bookRepository->findBook($bookId);
            $user = $userRepository->findUser($userID);
            $userBook->setBookId($book);
            $userBook->setUserId($user);

            $userBookRepository->save($userBook, true);
        }

        return new JsonResponse(true);
    }

    /**
     * Listens for API and reacts by adding a review to the book review table.
     *
     * @param $bookId -> id of the book being displayed
     * @param $score -> score of the review
     * @param $comment -> comment of the review
     * @param BookRepository $bookRepository
     * @param UserRepository $userRepository
     * @param BookReviewsRepository $bookReviewsRepository
     * @param RequestStack $requestStack
     * @param EntityManagerInterface $entityManager
     * @return JsonResponse -> true since it doesnt need to display anything
     * @throws \Exception
     */
    #[Route('/write/{bookId}/{score}/{comment}', name: 'write')]
    public function writeReview($bookId, $score, $comment, BookRepository $bookRepository,
                                UserRepository $userRepository, BookReviewsRepository $bookReviewsRepository, RequestStack $requestStack, EntityManagerInterface $entityManager): Response
    {
        $bookReview = new BookReviews;

        $comment  = str_replace('_period_', '.', $comment);
        $comment  = str_replace('_space_', ' ', $comment);
        $comment  = str_replace('_question_', '?', $comment);
        $email = $requestStack->getSession()->get('email');
        //get id from email
        $auth = new auth($entityManager);
        $userId = $auth->getID($email);

        $book = $bookRepository->findBook($bookId);
        $user = $userRepository->findUser($userId);
        $bookReview->setBookId($book);
        $bookReview->setUserId($user);
        $bookReview->setScore($score);
        $bookReview->setComment($comment);

        $timeZone = new \DateTimeZone('Europe/Brussels');
        $bookReview->setDateAdded(new \DateTime('now', $timeZone));

        $bookReviewsRepository->save($bookReview, true);

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
     * @throws Exception
     */
    #[Route('/review/{bookId}/{offset}', name: 'review')]
    public function review($bookId, $offset, BookReviewsRepository $bookReviewsRepository): Response
    {
        $reviews = $bookReviewsRepository->getReviews($offset, 6,$bookId);
        return new JsonResponse($reviews);

    }


    /**
     * returns true if the user is logged in
     *
     * @param RequestStack $requestStack
     * @return bool
     */
    private function checkSession(RequestStack $requestStack): bool
    {
        $session = $requestStack->getSession();
        $auth = new auth($this->doctrine->getManager());
        return($auth->isLogged($session));
    }

}