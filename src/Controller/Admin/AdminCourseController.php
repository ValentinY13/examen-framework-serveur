<?php

namespace App\Controller\Admin;

use App\Entity\Course;
use App\Form\CourseType;
use App\Repository\CourseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class AdminCourseController extends AbstractController
{

    public function __construct(
        private readonly SluggerInterface $slugger,
    ) {
    }

    #[Route('/admin/course', name: 'app_admin_course')]
    public function index(CourseRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        $courses  = $repository->findBy([], ['is_published' => 'DESC', 'created_at' => 'DESC']);

        $pagination = $paginator->paginate($courses, $request->query->getInt('page', 1), 7);
        return $this->render('admin/course/index.html.twig', [
            'courses' => $pagination,
        ]);
    }

    #[Route('/admin/course/add', name: 'app_admin_course_add')]
    public function add(Request $request, EntityManagerInterface $manager): Response
    {
        $course  = new Course();
        $form = $this->createForm(CourseType::class, $course);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $course->setPublished(true);
            $course->setSlug($this->slugger->slug($course->getName()));
            $manager->persist($course);
            $manager->flush();

            $this->addFlash(
                'success',
                'Formation ajoutée avec succès'
            );

            return $this->redirectToRoute('app_admin_course');
        }
        return $this->render('admin/course/add.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/admin/course/edit/{id}', name: 'app_admin_course_edit')]
    public function edit(Request $request, Course $course, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(CourseType::class, $course);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $course->setSlug($this->slugger->slug($course->getName()));
            $manager->flush();

            $this->addFlash(
                'success',
                'Formation modifiée avec succès'
            );

            return $this->redirectToRoute('app_admin_course');
        }

        return $this->render('admin/course/edit.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/admin/course/delete/{id}', name: 'app_admin_course_delete')]
    public function delete(Course $course, EntityManagerInterface $manager): Response
    {
        $manager->remove($course);
        $manager->flush();

        $this->addFlash(
            'success',
            'Formation supprimée avec succès'
        );

        return $this->redirectToRoute('app_admin_course');
    }

    #[Route('/admin/course/hide/{id}', name: 'app_admin_course_hide')]
    public function hide(Course $course, EntityManagerInterface $manager): Response
    {
        $message = 'masquée';
        $course->setPublished(!$course->isPublished());
        $manager->flush();

        if ($course->isPublished()) {
            $message = 'démasquée';
        }

        $this->addFlash(
            'success',
            "Formation $message avec succès"
        );

        return $this->redirectToRoute('app_admin_course');
    }
}
