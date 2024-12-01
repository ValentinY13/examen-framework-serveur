<?php

namespace App\Controller\Admin;

use App\Entity\Level;
use App\Form\LevelType;
use App\Repository\LevelRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AdminLevelController extends AbstractController
{
    #[Route('/admin/level', name: 'app_admin_level')]
    public function index(LevelRepository $repository, Request $request, PaginatorInterface $paginator): Response
    {
        $levels = $repository->findAll();

        $pagination = $paginator->paginate($levels, $request->query->getInt('page', 1), 5);

        return $this->render('admin/level/index.html.twig', [
            'levels' => $pagination,
        ]);
    }

    #[Route('/admin/level/add', name: 'app_admin_level_add')]
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {
        $level = new Level();

        $form = $this->createForm(LevelType::class, $level);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($level);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Niveau ajouté avec succès'
            );

            return $this->redirectToRoute('app_admin_level');
        }

        return $this->render('admin/level/add.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/admin/level/edit/{id}', name: 'app_admin_level_edit')]
    public function edit(Level $level, Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(LevelType::class, $level);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->flush();

            $this->addFlash(
                'success',
                'Niveau modifié avec succès'
            );

            return $this->redirectToRoute('app_admin_level');
        }
        return $this->render('admin/level/edit.html.twig', [
            'form' => $form
        ]);
    }
}
