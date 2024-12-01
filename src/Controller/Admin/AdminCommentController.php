<?php

namespace App\Controller\Admin;

use App\Entity\Comment;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AdminCommentController extends AbstractController
{
    #[Route('/admin/comment', name: 'app_admin_comment')]
    public function index(CommentRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        $comments = $repository->findAll();

        $pagination = $paginator->paginate($comments, $request->query->getInt('page', 1), 7);

        return $this->render('admin/comment/index.html.twig', [
            'comments' => $pagination,
        ]);
    }

    #[Route('/admin/comment/hide/{id}', name: 'app_admin_comment_hide')]
    public function hide(Comment $comment, EntityManagerInterface $manager): Response
    {
        $message = 'démasqué';

        $comment->setDisabled(!$comment->isDisabled());
        $manager->flush();

        if ($comment->isDisabled()) {
            $message = 'masqué';
        }

        $this->addFlash(
            'success',
            "Commentaire $message avec succès"
        );

        return $this->redirectToRoute('app_admin_comment');
    }
}
