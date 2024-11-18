<?php

namespace App\Controller;

use App\Service\StripeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CoursesRepository;
use App\Repository\LessonsRepository;

class StripeController extends AbstractController
{
    #[Route('/checkout/{type}/{id}', name: 'app_stripe_checkout')]
    public function checkout(string $type, int $id, CoursesRepository $coursesRepository, LessonsRepository $lessonsRepository): Response
    {
        if ($type === 'course') {
            $item = $coursesRepository->find($id);
            $description = 'Cursus : ' . $item->getName();
            $amount = $item->getPrice();
        } elseif ($type === 'lesson') {
            $item = $lessonsRepository->find($id);
            $description = 'Leçon : ' . $item->getName();
            $amount = $item->getPrice();
        } else {
            throw $this->createNotFoundException('Type invalide.');
        }
    
        // Simulation de Stripe Checkout
        // Remplacez cette partie par l'intégration réelle de Stripe
        return $this->render('buy/payment_success.html.twig', [
            'description' => $description,
            'amount' => $amount,
        ]);
    }
}
