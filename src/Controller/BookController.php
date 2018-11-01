<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\ContactType;
use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    /**
     * @var BookRepository
     */
    private $bookRepository;

    public function __construct(BookRepository $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }

    /**
     * @Route("/catalog", name="book.catalog")
     * @return Response
     */
    public function catalog(): Response
    {
        $books = $this->bookRepository->findAll();
        return $this->render('library/catalog.html.twig', [
            'books' => $books
        ]);
    }
    /**
     * @Route("/book/{slug}-{id}", name="book.show", requirements={"slug": "[a-z0-9\-]*", "id": "\d+"})
     * @param Book $book
     * @param string $slug
     * @return Response
     */
    public function show(Book $book, string $slug): Response
    {
        if ($book->getSlug() !== $slug) {
            return $this->redirectToRoute('book.show', [
                'id' => $book->getId(),
                'slug' => $book->getSlug()
            ], 301);
        }

        return $this->render('library/show.html.twig', [
            'book' => $book,
            //'otherBooks' => $otherBooks
        ]);
    }
}