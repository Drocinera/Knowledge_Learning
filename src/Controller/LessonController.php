<?php

namespace App\Controller;

use App\Repository\LessonsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LessonController extends AbstractController
{
    #[Route('/lesson/{id}', name: 'app_lesson_access')]
public function showLesson(int $id, LessonsRepository $lessonsRepository): Response
{
    $lesson = $lessonsRepository->find($id);

    if (!$lesson) {
        throw $this->createNotFoundException('Leçon introuvable.');
    }

    return $this->render('lesson/show.html.twig', [
        'lesson' => $lesson,
    ]);
}

}
