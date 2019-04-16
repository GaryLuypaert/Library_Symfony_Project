<?php


namespace App\Controller;


use App\Entity\User;
use App\Repository\BookRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function admin(UserRepository $userRepository, BookRepository $bookRepository) {

        $userRepository = $userRepository->findAll();
        $allBookRepository = $bookRepository->findAll();
        $booksWithoutImage = $bookRepository->announcementWithoutImage();

        return $this->render("admin/admin.html.twig", [
            'users' => $userRepository,
            'books' => $allBookRepository,
            'booksWithoutImage' => $booksWithoutImage,
        ]);
    }

    /**
     * @Route("/admin/delete/{id}", name="delete_user")
     */
    public function deleteUser(User $user, EntityManagerInterface $em, UserRepository $userRepository) {

        $userRepository = $userRepository->findAll();

        $em->remove($user);
        $em->flush();

        $this->addFlash("notice", "L'utilisateur a bien été supprimé");

        return $this->render('admin/admin.html.twig', [
            'users' => $userRepository,
        ]);
    }

}