<?php

namespace App\Controller\Admin;

use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin.home")
     * @param BookRepository $bookRepository
     * @return Response
     */
    public function home(BookRepository $bookRepository): Response
    {
        $books = $bookRepository->findByLimit(5);
        return $this->render('admin/home.html.twig', compact('books'));
    }

}