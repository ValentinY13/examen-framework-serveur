<?php

namespace App\Controller\Admin;

use App\Entity\News;
use App\Form\NewsType;
use App\Repository\NewsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class AdminNewsController extends AbstractController
{

    public function __construct(private readonly SluggerInterface $slugger) {
    }

    #[Route('/admin/news', name: 'app_admin_news')]
    public function index(NewsRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        $news = $repository->findBy([], ['created_at' => 'DESC']);

        $pagination = $paginator->paginate($news, $request->query->getInt('page', 1), 7);

        return $this->render('admin/news/index.html.twig', [
            'news' => $pagination,
        ]);
    }

    #[Route('/admin/news/add', name: 'app_admin_news_add')]
    public function add(Request $request, EntityManagerInterface $manager): Response
    {
        $news = new News();

        $form = $this->createForm(NewsType::class, $news);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $news->setSlug($this->slugger->slug($news->getName()));
            $manager->persist($news);
            $manager->flush();

            $this->addFlash(
                'success',
                "Actualités ajoutée avec succès"
            );

            return $this->redirectToRoute('app_admin_news');
        }

        return $this->render('admin/news/add.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/admin/news/edit/{id}', name: 'app_admin_news_edit')]
    public function edit(Request $request, News $news, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(NewsType::class, $news);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->flush();

            $this->addFlash(
                'success',
                "Actualités modifiée avec succès"
            );

            return $this->redirectToRoute('app_admin_news');
        }

        return $this->render('admin/news/edit.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/admin/news/delete/{id}', name: 'app_admin_news_delete')]
    public function delete(News $news, EntityManagerInterface $manager): Response
    {
        $manager->remove($news);
        $manager->flush();

        $this->addFlash(
            'success',
            "Actualités supprimée avec succès"
        );

        return $this->redirectToRoute('app_admin_news');
    }
}
