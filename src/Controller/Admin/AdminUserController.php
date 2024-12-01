<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AdminUserController extends AbstractController
{
    #[Route('/admin/user', name: 'app_admin_user')]
    public function index(UserRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        $user = $repository->findBy([], ['is_disabled' => 'ASC']);

        $pagination = $paginator->paginate($user, $request->query->getInt('page', 1), 10);

        return $this->render('admin/users/index.html.twig', [
            'users' => $pagination,
        ]);
    }

    #[Route('/admin/user/hide/{id}', name: 'app_admin_user_hide')]
    public function hide(User $user, EntityManagerInterface $manager): Response
    {
        $message = 'démasqué';

        $user->setDisabled(!$user->isDisabled());
        $manager->flush();

        if ($user->isDisabled()) {
            $message = 'masqué';
        }

        $this->addFlash(
            'success',
            "Utilisateur $message avec succès"
        );

        return $this->redirectToRoute('app_admin_user');
    }

    #[Route('/admin/user/delete/{id}', name: 'app_admin_user_delete')]
    public function delete(User $user, EntityManagerInterface $manager): Response
    {
        $manager->remove($user);
        $manager->flush();

        $this->addFlash(
            'success',
            "Utilisateur supprimé avec succès"
        );

        return $this->redirectToRoute('app_admin_user');
    }
}
