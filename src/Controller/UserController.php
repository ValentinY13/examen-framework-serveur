<?php

namespace App\Controller;

use App\Form\ResetPasswordType;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    #[Route('/mon-profil', name: 'app_user')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig');
    }

    #[Route('/modifier-profil', name: 'app_user_edit')]
    public function editProfile(EntityManagerInterface $manager, Request $request): Response
    {
        $user  = $this->getUser();

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $manager->flush();
            return $this->redirectToRoute('app_user');
        }
        return $this->render('user/edit.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/nouveau-mot-de-passe', name: 'app_user_edit_password')]
    public function editPassword(Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $user = $this->getUser();
        $formPassword = $this->createForm(ResetPasswordType::class, $user, [
            'edit' => true
        ]);
        $formPassword->handleRequest($request);
        if ($formPassword->isSubmitted() && $formPassword->isValid()) {
            $oldPassword = $formPassword->get('password')->getData();

            if (!$userPasswordHasher->isPasswordValid($user, $oldPassword)) {
                var_dump('mot de passe incorrect');
                return $this->render('user/editpassword.html.twig', [
                    'formPassword' => $formPassword,
                ]);
            }

            $newPassword = $formPassword->get('newPassword')->getData();
            $user->setPassword($userPasswordHasher->hashPassword($user, $newPassword));
            $user->setUpdatedAt(new \DateTimeImmutable());
            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('app_user');
        }
        return $this->render('user/editpassword.html.twig', [
            'formPassword' => $formPassword,
        ]);
    }
}
