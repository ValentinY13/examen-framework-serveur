<?php

namespace App\Controller;

use App\Repository\NewsRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class NewsController extends AbstractController
{
    #[Route('/news', name: 'app_news')]
    public function index(NewsRepository $repository, Request $request, PaginatorInterface $paginator): Response
    {
        $news = $repository->findBy([], ['created_at' => 'DESC']);

        $paginator = $paginator->paginate($news, $request->query->getInt('page', 1), 6);

        return $this->render('news/index.html.twig', [
            'news' => $paginator,
        ]);
    }
}
