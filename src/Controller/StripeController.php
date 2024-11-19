<?php

namespace App\Controller;

use App\Repository\CoursesRepository;
use App\Repository\LessonsRepository;
use App\Service\StripeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class StripeController extends AbstractController
{
    #[Route('/checkout/{type}/{id}', name: 'app_stripe_checkout')]
    public function checkout(
        string $type,
        int $id,
        CoursesRepository $coursesRepository,
        LessonsRepository $lessonsRepository,
        StripeService $stripeService
    ): Response {
        if ($type === 'course') {
            $item = $coursesRepository->find($id);
            $description = 'Cursus : ' . $item->getName();
            $price = $item->getPrice();
        } elseif ($type === 'lesson') {
            $item = $lessonsRepository->find($id);
            $description = 'Leçon : ' . $item->getName();
            $price = $item->getPrice();
        } else {
            throw $this->createNotFoundException('Type invalide.');
        }

        $successUrl = $this->generateUrl('app_payment_success', [], UrlGeneratorInterface::ABSOLUTE_URL);
        $cancelUrl = $this->generateUrl('app_payment_cancel', [], UrlGeneratorInterface::ABSOLUTE_URL);

        $session = $stripeService->createCheckoutSession(
            [
                [
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => ['name' => $description],
                        'unit_amount' => $price * 100,
                    ],
                    'quantity' => 1,
                ],
            ],
            $successUrl,
            $cancelUrl
        );

        return $this->redirect($session->url);
    }

    #[Route('/payment/success', name: 'app_payment_success')]
    public function success(): Response
    {
        return $this->render('payment/success.html.twig');
    }

    #[Route('/payment/cancel', name: 'app_payment_cancel')]
    public function cancel(): Response
    {
        return $this->render('payment/cancel.html.twig');
    }
}
