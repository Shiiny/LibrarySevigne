<?php

namespace App\Controller\Admin;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminBookController extends AbstractController
{
    /**
     * @var BookRepository
     */
    private $bookRepository;
    /**
     * @var ObjectManager
     */
    private $manager;

    public function __construct(BookRepository $bookRepository, ObjectManager $manager)
    {
        $this->bookRepository = $bookRepository;
        $this->manager = $manager;
    }

    /**
     * @Route("/admin/cours", name="admin.book.index")
     * @return Response
     */
    public function index(): Response
    {
        $books = $this->bookRepository->findAll();

        return $this->render('admin/book/index.html.twig', compact('books'));
    }

    /**
     * @Route("/admin/book/create", name="admin.book.create")
     * @param Request $request
     * @return Response
     */
    public function create(Request $request): Response
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->persist($book);
            $this->manager->flush();
            $this->addFlash('success', "Nouveau cours crée");

            return $this->redirectToRoute('admin.book.index');
        }

        return $this->render('admin/book/new.html.twig', [
            'book' => $book,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/book/{id}", name="admin.book.edit", methods="GET|POST")
     * @param Book $book
     * @param Request $request
     * @return Response
     */
    public function edit(Book $book, Request $request): Response
    {
        $form = $this->createForm(BookType::class, $book);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->flush();
            $this->addFlash('success', "Cours bien modifié");
            return $this->redirectToRoute('admin.book.index');
        }

        return $this->render('admin/book/edit.html.twig', [
            'book' => $book,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/book/{id}", name="admin.book.delete", methods="DELETE")
     * @param Book $book
     * @param Request $request
     * @return Response
     */
    public function delete(Book $book, Request $request): Response
    {
        if ($this->isCsrfTokenValid('delete' . $book->getId(), $request->get('_token'))) {
            $this->manager->remove($book);
            $this->manager->flush();
        }
        $this->addFlash('success', "Cours bien supprimé");
        return $this->redirectToRoute('admin.book.index');
    }
}
