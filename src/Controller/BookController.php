<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\BookSearch;
use App\Filters\Filter;
use App\Repository\BookRepository;
use App\Repository\CategoryRepository;
use Knp\Component\Pager\PaginatorInterface;
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
    /**
     * @var CategoryRepository
     */
    private $categoryRepository;

    public function __construct(BookRepository $bookRepository, CategoryRepository $categoryRepository)
    {
        $this->bookRepository = $bookRepository;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @Route("/catalog", name="book.catalog")
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @param Filter $filter
     * @return Response
     */
    public function catalog(PaginatorInterface $paginator, Request $request, Filter $filter, CategoryRepository $categoryRepository): Response
    {
        $books = $paginator->paginate(
            $this->bookRepository->findAllBooksQuery(),
            $request->query->getInt('page', 1),
            10
        );
        $filters = $filter->filterAuthorAndCategory($this->bookRepository->findAll());
        dump($filters);
        /*foreach ($filters['categories'] as $key => $category) {
            //dump($key, $category);
            $countCat = $this->bookRepository->findBy(['category' => $key]);
            dump(count($countCat));
        }*/
        //die();
        return $this->render('library/catalog.html.twig', [
            'books' => $books,
            'filters' => $filters,
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
        $authorBooks = $this->bookRepository->findByAuthor($book->getAuthor(), $book->getId());

        return $this->render('library/show.html.twig', [
            'book' => $book,
            'authorBooks' => $authorBooks
        ]);
    }

    /**
     * @Route("/catalog/book", name="book.search")
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    public function search(PaginatorInterface $paginator, Request $request, Filter $filter): Response
    {
        $search = new BookSearch();
        if ($request->get('search') === '') {
            return $this->redirectToRoute('book.catalog', [],301);
        }
        $search->setSearch($request->get('search'));

        $books = $paginator->paginate(
            $this->bookRepository->findAllBooksQuery($search),
            $request->query->getInt('page', 1),
            10
        );

        $filters = $filter->filterAuthorAndCategory($books);

        return $this->render('library/catalog.html.twig', [
            'books' => $books,
            'filters' => $filters,
        ]);
    }
}