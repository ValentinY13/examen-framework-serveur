<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\CategoryRepository;
use App\Repository\CommentRepository;
use App\Repository\CourseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CourseController extends AbstractController
{

    #[Route('/courses', name: 'app_courses')]
    public function index(CourseRepository $repository, CategoryRepository $category, Request $request, PaginatorInterface $paginator): Response
    {
        $courses = $repository->findBy(['is_published' => true], ['created_at' => 'DESC']);
        $categories = $category->findAll();

        $pagination = $paginator->paginate($courses, $request->query->getInt('page', 1), 6);

        return $this->render('course/courses.html.twig', [
            'courses' => $pagination,
            'categories' => $categories
        ]);
    }

    #[Route('/courses/{slug}', name: 'app_detail')]
    public function show($slug, CourseRepository $repository, CommentRepository $commentRepository, Request $request, Security $security, EntityManagerInterface $manager): Response
    {
        $course = $repository->findOneBy(['slug' => $slug]);
        $category = $course->getCategory();
        $id = $course->getId();

        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $security->getUser();

            if (!$user) {
                return $this->redirectToRoute('app_login');
            }

            if ($commentRepository->findOneBy(['user' => $user, 'course' => $course])) {
                $this->addFlash(
                    'error',
                    'Commentaire déjà existant'
                );
            } else {
                $comment
                    ->setUser($user)
                    ->setCourse($course)
                    ->setCreatedAt(new \DateTimeImmutable())
                    ->setDisabled(true)
                ;

                $manager->persist($comment);
                $manager->flush();
                $this->addFlash(
                    'success',
                    'Commentaire ajouté'
                );
            }

            return $this->redirectToRoute('app_detail', ['slug' => $slug]);
        }

        $user = $security->getUser();
        $userComment = null;
        if ($user) {
            $userComment = $commentRepository->findOneBy(['user' => $user, 'course' => $course]);
        }

        // Trouver 3 cours liés de la même catégorie, en excluant le cours actuel
        $relatedCourses = $repository->getRelatedCourses($category, $id);

        return $this->render('course/detail.html.twig', [
            'course' => $course,
            'form' => $form,
            'relatedCourses' => $relatedCourses,
            'userComment' => $userComment
        ]);
    }
}