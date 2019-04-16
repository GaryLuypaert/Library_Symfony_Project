<?php
/**
 * Created by PhpStorm.
 * User: garyluypaert
 * Date: 2019-03-20
 * Time: 00:35
 */

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Image;
use App\Entity\Keyword;
use App\Form\BookType;
use App\Repository\BookRepository;
use App\Services\ImageHandler;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home(BookRepository $bookRepository) {

        $books = $bookRepository->findAll();

        return $this->render('app/index.html.twig', [
            'books' => $books,
        ]);
    }

    /**
     * @Route("/show/{id}", name="book_details")
     */
    public function bookDetails(Book $book) {

        return $this->render('app/details.html.twig', [
            'book' => $book,
        ]);
    }

    /**
     * @Route("/book/add", name="add_page")
     */
    public function addBook(EntityManagerInterface $em, Request $request, ImageHandler $handler) {

        $path = $this->getParameter('kernel.project_dir').'/public/upload';

        $form = $this->createForm(BookType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $book = $form->getData();
            $user = $this->getUser();
            $book->setUser($user);

            $em->persist($book);
            $em->flush();

            $this->addFlash("notice", "Le livre a bien été ajouté");

            return $this->redirectToRoute("home");
        }

        return $this->render("app/add.html.twig", [
            "form" => $form->createView(),
        ]);

    }

    /**
     * @Route("/book/edit/{id}", name="edit_page")
     */
    public function editBook(Book $book, EntityManagerInterface $em, Request $request) {

        $path = $this->getParameter('kernel.project_dir').'/public/upload';
        $form = $this->createForm(BookType::class, $book, ['path' => $path]);
        $this->denyAccessUnlessGranted('EDIT', $book);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $path = $this->getParameter('kernel.project_dir').'/public/upload';

            $em->flush();

            $this->addFlash("notice", "Le livre a bien été mis à jour");

            return $this->redirectToRoute("home", [
                'id' => $book->getId(),
            ]);
        }

        return $this->render("app/edit.html.twig", [
            "book" => $book,
            "form" => $form->createView(),
        ]);
    }

    /**
     * @Route("/book/delete/{id}", name="delete_page")
     */
    public function deleteBook(Book $book, EntityManagerInterface $em) {

        $this->denyAccessUnlessGranted('DELETE', $book);

        $em->remove($book);

        $em->flush();

        $this->addFlash("notice", "Le livre a bien été supprimé");

        return $this->redirectToRoute("home");
    }

    /**
     * @Route("/asc", name="order_asc")
     */
    public function orderAsc(BookRepository $bookRepository) {

        $books = $bookRepository->orderAsc();

        return $this->render('app/index.html.twig', [
            'books' => $books,
        ]);
    }

    /**
     * @Route("/delete/keyword/{id}",
     *     name="delete_keyword",
     *     methods={"POST"},
     *     condition="request.headers.get('X-Requested-With') matches '/XMLHttpRequest/i'")
     */
    public function deleteKeyword(Keyword $keyword, EntityManagerInterface $em) {
        $em->remove($keyword);
        $em->flush();

        return new JsonResponse();
    }



}