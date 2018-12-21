<?php

namespace App\Controller;

use App\Entity\BookSearch;
use App\Form\BookSearchType;
use App\Form\ContactType;
use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LibraryController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @param BookRepository $repository
     * @return Response
     */
    public function index(BookRepository $repository) :Response
    {
        $books = $repository->findByLimitAndDate(12, 'yearBook');

        return $this->render('library/home.html.twig', compact('books'));
    }

    /**
     * @return Response
     */
    public function search(): Response
    {
        $search = new BookSearch();

        $form = $this->createForm(BookSearchType::class, $search);

        return $this->render('library/component/_searchForm.html.twig', ["form" => $form->createView()]);
    }

    /**
     * @Route("/contact", name="contact")
     * @param Request $request
     * @return Response
     */
    public function contact(Request $request) :Response
    {
        $formContact = $this->createForm(ContactType::class);

        $formContact->handleRequest($request);
        if ($formContact->isSubmitted() && $formContact->isValid()) {
            //$data = $formContact->getData();
            //$this->get('library.mailer')->sendContactMail($data);

            $this->addFlash('info', "Un e-mail contenant votre message a été envoyé");
            return $this->redirectToRoute('contact');
        }

        return $this->render('library/contact.html.twig', [
            'formContact' => $formContact->createView()
        ]);
    }

    /**
     * @Route("/legal", name="legal")
     * @return Response
     */
    public function legal(): Response
    {
        return $this->render('library/legale.html.twig');
    }
}
