<?php

namespace App\Services;

use App\Entity\Purchases;
use App\Entity\Comprise;
use App\Entity\Courses;
use App\Entity\Lessons;
use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Service responsible for managing purchases and their associated details.
 */

class PurchaseManager
{
    /**
     * @var EntityManagerInterface $entityManager Doctrine entity manager for database operations.
     */

    private EntityManagerInterface $entityManager;

    /**
     * Constructor.
     *
     * @param EntityManagerInterface $entityManager The entity manager for handling database operations.
     */

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Creates a new purchase for a user and associates it with a course or lesson.
     *
     * @param Users $user The user making the purchase.
     * @param string $type The type of item being purchased ('course' or 'lesson').
     * @param int $itemId The ID of the course or lesson being purchased.
     * @param float $price The price of the purchased item.
     *
     * @throws \Exception If the course or lesson with the given ID does not exist.
     */

    public function createPurchase(Users $user, string $type, int $itemId, float $price): void
    {
        try {
            // Create a new purchase
            $purchase = new Purchases();
            $purchase->setUser($user);
            $purchase->setPurchaseDate(new \DateTimeImmutable());
            $this->entityManager->persist($purchase);
        
            // Create a new comprise (purchase details)
            $comprise = new Comprise();
            $comprise->setPurchase($purchase);
            $comprise->setPrice($price);
            $comprise->setAccessGranted(true);
        
            // Associate the purchase with a course or lesson based on the type
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
            error_log('Erreur lors de la création de l\'achat : ' . $e->getMessage());
            throw $e; // Re-throw to be handled higher up
        }
    }
}
