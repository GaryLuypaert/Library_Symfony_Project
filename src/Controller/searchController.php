<?php


namespace App\Controller;


use App\Form\SearchBookType;
use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class searchController extends AbstractController
{
    /**
     * @Route("/search/book", name="search_book")
     */
    public function searchBook(Request $request, BookRepository $bookRepository) {

        $books = [];
        $searchBookForm = $this->createForm(SearchBookType::class);

        $searchBookForm->handleRequest($request);

        if($searchBookForm->isSubmitted() && $searchBookForm->isValid()) {
            $criteria = $searchBookForm->getData();

            $books = $bookRepository->searchBook($criteria);
            dump($books);
        }

        return $this->render('search/book.html.twig', [
            'search_form' => $searchBookForm->createView(),
            'books' => $books,
        ]);
    }
}