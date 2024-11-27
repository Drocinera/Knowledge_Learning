<?php

namespace App\Service;

use App\Entity\Purchases;
use App\Entity\Comprise;
use App\Entity\Courses;
use App\Entity\Lessons;
use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;

class PurchaseManager
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function createPurchase(Users $user, string $type, int $itemId, float $price): void
    {
        try {
            $purchase = new Purchases();
            $purchase->setUser($user);
            $purchase->setPurchaseDate(new \DateTimeImmutable());
            $this->entityManager->persist($purchase);
        
            $comprise = new Comprise();
            $comprise->setPurchase($purchase);
            $comprise->setPrice($price);
            $comprise->setAccessGranted(true);
        
            if ($type === 'course') {
                $course = $this->entityManager->getRepository(Courses::class)->find($itemId);
                if (!$course) {
                    throw new \Exception("Le cours avec l'ID $itemId n'existe pas.");
                }
                $comprise->setCourse($course);
            } elseif ($type === 'lesson') {
                $lesson = $this->entityManager->getRepository(Lessons::class)->find($itemId);
                if (!$lesson) {
                    throw new \Exception("La leçon avec l'ID $itemId n'existe pas.");
                }
                $comprise->setLesson($lesson);
            }
        
            $this->entityManager->persist($comprise);
            $this->entityManager->flush();
        } catch (\Exception $e) {
            // Log the error for debugging purposes
            error_log('Erreur lors de la création de l\'achat : ' . $e->getMessage());
            throw $e; // Re-throw to be handled higher up
        }
    }
}
