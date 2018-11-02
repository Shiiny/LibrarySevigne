<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminCategoryController extends AbstractController
{
    /**
     * @var ObjectManager
     */
    private $manager;
    /**
     * @var CategoryRepository
     */
    private $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository, ObjectManager $manager)
    {
        $this->manager = $manager;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @Route("/admin/category", name="admin.category.index")
     * @return Response
     */
    public function index(): Response
    {
        $categories = $this->categoryRepository->findAll();

        return $this->render('admin/category/index.html.twig', compact('categories'));
    }

    /**
     * @Route("/admin/category/create", name="admin.category.create")
     * @param Request $request
     * @return Response
     */
    public function create(Request $request): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->persist($category);
            $this->manager->flush();
            $this->addFlash('success', "Nouvelle catégorie créée");

            return $this->redirectToRoute('admin.category.index');
        }

        return $this->render('admin/category/new.html.twig', [
            'category' => $category,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/category/{id}", name="admin.category.edit", methods="GET|POST")
     * @param Category $category
     * @param Request $request
     * @return Response
     */
    public function edit(Category $category, Request $request): Response
    {
        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->flush();
            $this->addFlash('success', "Catégorie bien modifiée");
            return $this->redirectToRoute('admin.category.index');
        }

        return $this->render('admin/category/edit.html.twig', [
            'category' => $category,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/category/{id}", name="admin.category.delete", methods="DELETE")
     * @param Category $category
     * @param Request $request
     * @return Response
     */
    public function delete(Category $category, Request $request): Response
    {
        if ($this->isCsrfTokenValid('delete' . $category->getId(), $request->get('_token'))) {
            $this->manager->remove($category);
            $this->manager->flush();
        }
        $this->addFlash('success', "Catégorie bien supprimée");
        return $this->redirectToRoute('admin.category.index');
    }
}