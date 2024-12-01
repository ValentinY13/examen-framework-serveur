<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AdminCategoryController extends AbstractController
{
    #[Route('/admin/category', name: 'app_admin_category')]
    public function index(CategoryRepository $repository, Request $request, PaginatorInterface $paginator): Response
    {
        $categories = $repository->findAll();

        $pagination = $paginator->paginate($categories, $request->query->getInt('page', 1), 5);

        return $this->render('admin/category/index.html.twig', [
            'categories' => $pagination,
        ]);
    }

    #[Route('/admin/category/add', name: 'app_admin_category_add')]
    public function add(Request $request, EntityManagerInterface $manager): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($category);
            $manager->flush();

            $this->addFlash(
                'success',
                'Catégorie ajoutée avec succès'
            );

            return $this->redirectToRoute('app_admin_category');
        }

        return $this->render('admin/category/add.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/admin/category/edit/{id}', name: 'app_admin_category_edit')]
    public function edit(Category $category, Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->flush();

            $this->addFlash(
                'success',
                'Catégorie modifiée avec succès'
            );

            return $this->redirectToRoute('app_admin_category');
        }
        return $this->render('admin/category/edit.html.twig', [
            'form' => $form
        ]);
    }
}
