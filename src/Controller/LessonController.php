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

class LessonController extends AbstractController
{
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

private function addCertification(Users $user, string $courseName, EntityManagerInterface $entityManager): void
    {
        dump('Adding certification for user:', $user->getId()); // Vérifie l'ID utilisateur
        dump('Course name:', $courseName); // Vérifie le nom du cursus

        $certification = new Certifications();
        $certification->setUser($user);
        $certification->setTitle('Knowledge Learning Certification');
        $certification->setDescription("Certification obtenue pour le cursus : $courseName.");
        $certification->setDateAwarded(new \DateTimeImmutable());

        $entityManager->persist($certification);
        $entityManager->flush();

        $this->addFlash('success', "Félicitations ! Vous avez obtenu une certification pour le cursus : $courseName.");
    }


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

    // Vérifiez si l'utilisateur a déjà validé la leçon
    $alreadyValidated = $entityManager->getRepository(Valid::class)
        ->findOneBy(['user' => $user, 'lesson' => $lesson]);

    if ($alreadyValidated) {
        $this->addFlash('warning', 'Vous avez déjà validé cette leçon.');
        return $this->redirectToRoute('app_lesson_access', ['id' => $id]);
    }

    // Créez une nouvelle validation
    $valid = new Valid();
    $valid->setUser($user)
        ->setLesson($lesson)
        ->setDateValidated(new \DateTimeImmutable());

    $entityManager->persist($valid);
    $entityManager->flush();

    $this->addFlash('success', 'Leçon validée avec succès.');

    // Vérifiez si toutes les leçons du cursus sont validées
    $course = $lesson->getCourse();
    $allLessons = $course->getLessons();

    // Récupérez toutes les validations pour cet utilisateur dans ce cursus
    $validatedLessons = $entityManager->getRepository(Valid::class)
        ->findBy(['user' => $user]);

        dump('Validated lessons:', $validatedLessons);

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