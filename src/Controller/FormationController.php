<?php

namespace App\Controller;

use App\Repository\ThemesRepository;
use App\Repository\CoursesRepository;
use App\Repository\LessonsRepository;
use App\Repository\CompriseRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * FormationController
 * 
 * Controller for managing formations and related actions.
 */
class FormationController extends AbstractController
{
    /**
     * Renders the main formation page.
     *
     * @return Response A response object that redirects or renders a template
     */

    #[Route('/formation', name: 'app_formation')]
    public function index(): Response
    {
        return $this->render('formation/index.html.twig', [
            'controller_name' => 'FormationController',
        ]);
    }

    /**
     * Displays a specific formation and its related courses and lessons.
     *
     * @param int $id The ID of the formation.
     * @param ThemesRepository $themesRepository Repository for themes.
     * @param CompriseRepository $compriseRepository Repository for checking access.
     * 
     * @return Response A response object that redirects or renders a template
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If the formation is not found.
     */

    #[Route('/formation/{id}', name: 'app_formation_show')]
    public function show(
        int $id, 
        ThemesRepository $themesRepository,
        CompriseRepository $compriseRepository
    ): Response {
        $user = $this->getUser();
        $formation = $themesRepository->find($id);

        if (!$formation) {
            throw $this->createNotFoundException('Cette formation n\'existe pas.');
        }

        $courses = $formation->getCourses();
        $accessMap = [];

        if ($user) {
            foreach ($courses as $course) {
                $courseAccess = $compriseRepository->hasAccess($user, 'course', $course->getId());
                $accessMap['course_' . $course->getId()] = $courseAccess;

                foreach ($course->getLessons() as $lesson) {
                    $lessonAccess = $compriseRepository->hasAccess($user, 'lesson', $lesson->getId());
                    if ($courseAccess) {
                        $lessonAccess = true;
                    }

                    $accessMap['lesson_' . $lesson->getId()] = $lessonAccess;
                }
            }
        }

        return $this->render('formation/show.html.twig', [
            'formation' => $formation,
            'courses' => $courses,
            'accessMap' => $accessMap,
        ]);
    }

    /**
     * Displays a summary page for purchasing a course.
     *
     * @param int $id The ID of the course.
     * @param CoursesRepository $coursesRepository Repository for courses.
     * 
     * @return Response A response object that redirects or renders a template
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If the course is not found.
     */

    #[Route('/buy/course/{id}/summary', name: 'app_buy_course_summary')]
    public function buyCourseSummary(int $id, CoursesRepository $coursesRepository): Response
    {
        $course = $coursesRepository->find($id);

        if (!$course) {
            throw $this->createNotFoundException('Cursus introuvable.');
        }

        return $this->render('buy/course_summary.html.twig', [
            'course' => $course,
        ]);
    }

    /**
     * Displays a summary page for purchasing a lesson.
     *
     * @param int $id The ID of the lesson.
     * @param LessonsRepository $lessonsRepository Repository for lessons.
     * 
     * @return Response A response object that redirects or renders a template
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If the lesson is not found.
     */
    
    #[Route('/buy/lesson/{id}/summary', name: 'app_buy_lesson_summary')]
    public function buyLessonSummary(int $id, LessonsRepository $lessonsRepository): Response
    {
        $lesson = $lessonsRepository->find($id);

        if (!$lesson) {
            throw $this->createNotFoundException('Leçon introuvable.');
        }

        return $this->render('buy/lesson_summary.html.twig', [
            'lesson' => $lesson,
        ]);
    }
}
