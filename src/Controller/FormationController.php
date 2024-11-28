<?php

namespace App\Controller;

use App\Repository\ThemesRepository;
use App\Repository\CoursesRepository;
use App\Repository\LessonsRepository;
use App\Repository\CompriseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class FormationController extends AbstractController
{
    #[Route('/formation', name: 'app_formation')]
    public function index(): Response
    {
        return $this->render('formation/index.html.twig', [
            'controller_name' => 'FormationController',
        ]);
    }

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
    
        // Récupérer directement les cursus associés à la formation
        $courses = $formation->getCourses();
        $accessMap = [];
    
        if ($user) {
            // Construire une map d'accès : [courseId => true/false, lessonId => true/false]
            foreach ($courses as $course) {
                // Accès au cursus
                $courseAccess = $compriseRepository->hasAccess($user, 'course', $course->getId());
                $accessMap['course_' . $course->getId()] = $courseAccess;
    
                // Accès aux leçons associées au cursus ou achetées individuellement
                foreach ($course->getLessons() as $lesson) {
                    $lessonAccess = $compriseRepository->hasAccess($user, 'lesson', $lesson->getId());
    
                    // Accorder l'accès à la leçon si le cursus est acheté
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
