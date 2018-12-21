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
     * @param null $element
     * @return array
     */
    public function filterAuthorAndCategory($element)
    {
        $value = 1;

        $arrayAuthors['authors'] = [];
        $arrayCategory['categories'] = [];

        foreach ($element as $elt)
        {
            if (array_key_exists($elt->getAuthor(), $arrayAuthors['authors']))
            {
                $arrayAuthors['authors'][$elt->getAuthor()] = $arrayAuthors['authors'][$elt->getAuthor()] + 1;
            }
            else {
                $arrayAuthors['authors'] += [$elt->getAuthor() => $value];
            }


            if (array_key_exists($elt->getCategory()->getTitle(), $arrayCategory['categories']))
            {
                $arrayCategory['categories'][$elt->getCategory()->getTitle()] = $arrayCategory['categories'][$elt->getCategory()->getTitle()] + 1;
            }
            else {
                $arrayCategory['categories'] += [$elt->getCategory()->getTitle() => $value];
            }
        }
        $arrayFilter = array_merge($arrayAuthors, $arrayCategory);


        return $arrayFilter;
    }
}