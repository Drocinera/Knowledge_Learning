<?php

namespace App\Controller;

use App\Repository\CoursesRepository;
use App\Repository\LessonsRepository;
use App\Entity\Users;
use App\Service\StripeService;
use App\Service\PurchaseManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\ORM\EntityManagerInterface;

class StripeController extends AbstractController
{

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    #[Route('/checkout/{type}/{id}', name: 'app_stripe_checkout')]
    public function checkout(
        string $type,
        int $id,
        CoursesRepository $coursesRepository,
        LessonsRepository $lessonsRepository,
        StripeService $stripeService,
        SessionInterface $session
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

        $session->set('description', $description);
        $session->set('amount', $price);

        $successUrl = $this->generateUrl('app_payment_success', [], UrlGeneratorInterface::ABSOLUTE_URL);
        $cancelUrl = $this->generateUrl('app_payment_cancel', [], UrlGeneratorInterface::ABSOLUTE_URL);

        $stripeSession = $stripeService->createCheckoutSession(
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
            $cancelUrl,
            [
                'type' => (string) $type,
                'userId' => (string) $this->getUser()->getId(),
                'itemId' => (string) $id,
            ]
        );

        return $this->redirect($stripeSession->url);
    }

    #[Route('/payment/success', name: 'app_payment_success')]
    public function success(Request $request): Response
    {
        $session = $request->getSession();
        $description = $session->get('description');
        $amount = $session->get('amount');

        return $this->render('payment/success.html.twig', [
            'description' => $description,
            'amount' => $amount,
        ]);
    }

    #[Route('/payment/cancel', name: 'app_payment_cancel')]
    public function cancel(): Response
    {
        return $this->render('payment/cancel.html.twig');
    }

    #[Route('/stripe/webhook', name: 'stripe_webhook', methods: ['POST'])]
    public function stripeWebhook(Request $request, PurchaseManager $purchaseManager): Response
    {
        $payload = $request->getContent();
        $sigHeader = $request->headers->get('Stripe-Signature');
        $endpointSecret = $this->getParameter('stripe_webhook_secret');

        try {
            $event = \Stripe\Webhook::constructEvent($payload, $sigHeader, $endpointSecret);
        } catch (\UnexpectedValueException $e) {
            return new Response('Invalid payload', 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            return new Response('Invalid signature', 400);
        }

        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;
            $metadata = $session->metadata ?? [];

            if (!isset($metadata['type'], $metadata['itemId'], $metadata['userId'])) {
                return new Response('Invalid metadata', 400);
            }

            $type = $metadata['type'] ?? null;
            $itemId = $metadata['itemId'] ?? null;
            $userId = $metadata['userId'] ?? null;

            if (empty($type) || empty($itemId) || empty($userId)) {
                return new Response('Métadonnées Stripe manquantes ou incorrectes.', 400);
            }         

            if ($type && $itemId && $userId) {
                $price = $session->amount_total / 100;

                $user = $this->entityManager->getRepository(Users::class)->find($userId);
            }
            if (!$user) {
                return new Response('Utilisateur introuvable', Response::HTTP_NOT_FOUND);
            }

            $purchaseManager->createPurchase($user, $type, $itemId, $price);
        }

        return new Response('Webhook handled', 200);
    }
}