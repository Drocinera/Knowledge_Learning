<?php

namespace App\Controller;

use App\Repository\CourseRepository;
use App\Repository\ThemesRepository;
use App\Repository\CoursesRepository;
use App\Repository\LessonsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CourseController extends AbstractController
{
    #[Route('/course/{id}', name: 'app_course_access')]
public function showCourse(int $id, CoursesRepository $coursesRepository): Response
{
    $course = $coursesRepository->find($id);

    if (!$course) {
        throw $this->createNotFoundException('Cursus introuvable.');
    }

    return $this->render('course/show.html.twig', [
        'course' => $course,
        'lessons' => $course->getLessons(),
    ]);
}
}
