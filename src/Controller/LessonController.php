<?php

namespace App\Controller;

use App\Repository\LessonsRepository;
use App\Repository\CertificationsRepository;
use App\Entity\Valid;
use App\Entity\Users;
use App\Entity\Certifications;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * LessonController
 *
 * This controller manages lesson page and related-actions : Show lesson, validate lesson and add certification
 */
class LessonController extends AbstractController
{

    /**
     * Display the selected lesson.
     *
     * @param int $id The id of the lesson
     * @param LessonsRepository $lessonsRepository Repository for lessons. 
     * @param EntityManagerInterface $entityManager The Doctrine EntityManager for database operations.
     * 
     * @return Response A response object that redirects or renders a template
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If the lesson is not found.
     */

    #[Route('/lesson/{id}', name: 'app_lesson_access')]
    public function showLesson(int $id, LessonsRepository $lessonsRepository, EntityManagerInterface $entityManager): Response
    {
        $lesson = $lessonsRepository->find($id);
        $user = $this->getUser();

        if (!$lesson) {
            throw $this->createNotFoundException('Leçon introuvable.');
        }

        $isValidated = false;
        if ($user) {
            $isValidated = $entityManager->getRepository(Valid::class)
                ->findOneBy(['user' => $user, 'lesson' => $lesson]) !== null;
        }

        return $this->render('lesson/show.html.twig', [
            'lesson' => $lesson,
            'isValidated' => $isValidated,
    ]);
}

    /**
     * Add Certification after all lesson validate.
     *
     * @param Users $user Entity Users
     * @param LessonsRepository $courseName Link between course and lesson to obtain course data
     * @param EntityManagerInterface $entityManager The Doctrine EntityManager for database operations.
     * 
     * @return Response A response object that redirects or renders a template
     */

    private function addCertification(Users $user, string $courseName, EntityManagerInterface $entityManager): void
    {

        $certification = new Certifications();
        $certification->setUser($user);
        $certification->setTitle('Knowledge Learning Certification');
        $certification->setDescription("Certification obtenue pour le cursus : $courseName.");
        $certification->setDateAwarded(new \DateTimeImmutable());

        $entityManager->persist($certification);
        $entityManager->flush();

        $this->addFlash('success', "Félicitations ! Vous avez obtenu une certification pour le cursus : $courseName.");
    }

    /**
     * Validate lesson after clicking on the button.
     *
     * @param int $id The ID of the lesson
     * @param LessonsRepository $lessonsRepository  Lesson Repository
     * @param EntityManagerInterface $entityManager The Doctrine EntityManager for database operations.
     * @param ValidatorInterface $validator Symfony component for validation
     * 
     * @return Response A response object that redirects or renders a template
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If the role ID provided does not exist. If the user isn't login redirect to login page.
     */

    #[Route('/lesson/{id}/validate', name: 'app_lesson_validate')]
    public function validateLesson(
        int $id,
        LessonsRepository $lessonsRepository,
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator
    ): Response {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $lesson = $lessonsRepository->find($id);
        if (!$lesson) {
            throw $this->createNotFoundException('Leçon introuvable.');
        }

        $alreadyValidated = $entityManager->getRepository(Valid::class)
            ->findOneBy(['user' => $user, 'lesson' => $lesson]);

        if ($alreadyValidated) {
            $this->addFlash('warning', 'Vous avez déjà validé cette leçon.');
            return $this->redirectToRoute('app_lesson_access', ['id' => $id]);
        }

        $valid = new Valid();
        $valid->setUser($user)
            ->setLesson($lesson)
            ->setDateValidated(new \DateTimeImmutable());

        $entityManager->persist($valid);
        $entityManager->flush();

        $this->addFlash('success', 'Leçon validée avec succès.');

        $course = $lesson->getCourse();
        $allLessons = $course->getLessons();

        $validatedLessons = $entityManager->getRepository(Valid::class)
            ->findBy(['user' => $user]);


            $validatedLessonIds = array_map(
                fn($valid) => $valid->getLesson()->getId(),
                $validatedLessons
            );
            
            $allLessonIds = array_map(
                fn($lesson) => $lesson->getId(),
                $allLessons->toArray()
            );
            
            if (count(array_diff($allLessonIds, $validatedLessonIds)) === 0) {
                $this->addCertification($user, $course->getName(), $entityManager);
            }

        return $this->redirectToRoute('app_lesson_access', ['id' => $id]);
    }
}

