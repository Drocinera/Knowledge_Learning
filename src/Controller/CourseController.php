<?php

namespace App\Controller;

use App\Repository\CourseRepository;
use App\Repository\ThemesRepository;
use App\Repository\CoursesRepository;
use App\Repository\LessonsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * CourseController
 *
 * This controller manages courses page
 */
class CourseController extends AbstractController
{

    /**
     * Renders the main course page.
     *
     * @param int $id The ID of the course.
     * @param CoursesRepository $CourseRepository Repository for course.
     * 
     * @return Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If the course is not found.
     */

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
