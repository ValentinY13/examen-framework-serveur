<?php

namespace App\Controller;

use App\Repository\CourseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(CourseRepository $repository): Response
    {
        $threeLastCourses = $repository->findBy([], ['created_at' => 'DESC'], 3);
        return $this->render('home/index.html.twig', [
            'courses' => $threeLastCourses,
        ]);
    }
}
