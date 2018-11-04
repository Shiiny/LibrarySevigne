<?php

namespace App\Filters;

use App\Entity\Book;
use App\Entity\Category;
use App\Form\BookType;
use App\Form\CategoryType;
use App\Repository\BookRepository;

class Filter
{
    /**
     * @var BookRepository
     */
    private $bookRepository;

    /**
     * Filter constructor.
     * @param BookRepository $bookRepository
     */
    public function __construct(BookRepository $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }

    /**
     *
     * @return array
     */
    public function filterAuthorAndCategoryForSidebar()
    {
        $books = $this->bookRepository->findAll();

        $arrayAuthors['authors'] = [];
        $arrayCategory['categories'] = [];

        foreach ($books as $book)
        {
            $nameAuthor = $book->getAuthor();
            $titleCategory = $book->getCategory()->getTitle();

            if (!in_array($nameAuthor, $arrayAuthors['authors']))
            {
                array_push($arrayAuthors['authors'], $nameAuthor);
            }
            if (!in_array($titleCategory, $arrayCategory['categories']))
            {
                array_push($arrayCategory['categories'], $titleCategory);
            }
        }
        $arrayFilter = array_merge($arrayAuthors, $arrayCategory);

        return $arrayFilter;
    }
}