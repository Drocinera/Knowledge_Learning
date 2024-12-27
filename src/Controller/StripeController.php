<?php

namespace App\Controller;

use App\Repository\CoursesRepository;
use App\Repository\LessonsRepository;
use App\Entity\Users;

use App\Services\StripeService;
use App\Services\PurchaseManager;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\ORM\EntityManagerInterface;

/**
 * StripeController
 *
 * This controller manages Stripe external service , checkout, payment succes/cancel and recovery of information using the webhook.
 */
class StripeController extends AbstractController
{

    private EntityManagerInterface $entityManager;
    private StripeService $stripeService;

    public function __construct(EntityManagerInterface $entityManager, StripeService $stripeService)
    {
        $this->entityManager = $entityManager;
        $this->stripeService = $stripeService;
    }

    /**
     * render a checkout page with price for the selected type : Lesson or cursus. .
     *
     * @param string $type The selected type of content.
     * @param int $id the id of the selected content.
     * @param CoursesRepository $coursesRepository The course Repository.
     * @param LessonsRepository $lessonsRepository The Lesson Repository.
     * @param StripeService $stripeService The external service for Stripe .
     * @param SessionInterface $session The symfony component for session.
     * 
     * @return Response A response object that redirects or renders a template.
     * 
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If the Lesson or the course does not exist.
     * @throws \Symfony\Component\HttpKernel\Exception\AccessDeniedException If user isn't login
     */

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

        if (!$this->getUser()) {
            throw $this->createAccessDeniedException('Utilisateur non authentifié.');
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

    /**
     * Renders the succes payement page with summary information
     * 
     * @param Request $request The HTTP request object containing user data.
     *
     * @return Response  A response object that redirects or renders a template.
     */

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

    /**
     * Renders the cancel payement page
     *
     * @return Response  A response object that redirects or renders a template.
     */

    #[Route('/payment/cancel', name: 'app_payment_cancel')]
    public function cancel(): Response
    {
        return $this->render('payment/cancel.html.twig');
    }

    /**
     * retrieves information from the Stripe Webhook to create a new entry in the “purchases” table
     * 
     * @param Request $request The HTTP request object containing user data.
     * @param PurchaseManager $purchaseManager created Service for table "Pruchases" and "Comprise"
     *
     * @return Response  CCreate a valid entry in "purchases"
     * 
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If error with webhook, metadata or invalid user
     */

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
