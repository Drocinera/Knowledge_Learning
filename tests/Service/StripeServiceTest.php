<?php

namespace App\Tests\Services;

use App\Services\StripeService;
use PHPUnit\Framework\TestCase;
use Stripe\Checkout\Session;
use Stripe\Exception\ApiErrorException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Unit Test for "createCheckoutSession " in StripeService.
 * 
 */
class StripeServiceTest extends TestCase
{
    private $stripeService;

    protected function setUp(): void
{
    // Lire la clé Stripe depuis l'environnement
    $stripeSecretKey = getenv('STRIPE_SECRET_KEY') ?: 'test_secret_key';

    // Mock du ParameterBagInterface
    $parameterBagMock = $this->createMock(ParameterBagInterface::class);
    $parameterBagMock->method('get')
        ->with('stripe_secret_key')
        ->willReturn($stripeSecretKey);

    // Initialisation de StripeService avec le mock
    $this->stripeService = new StripeService($parameterBagMock);
}


    public function testCreateCheckoutSession(): void
    {
        $items = [
            [
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => ['name' => 'Test Product'],
                    'unit_amount' => 5000, // 50 EUR
                ],
                'quantity' => 1,
            ],
        ];
        $successUrl = 'https://example.com/success';
        $cancelUrl = 'https://example.com/cancel';

        try {
            $session = $this->stripeService->createCheckoutSession($items, $successUrl, $cancelUrl);
            $this->assertInstanceOf(Session::class, $session);
            $this->assertEquals($session->success_url, $successUrl);
            $this->assertEquals($session->cancel_url, $cancelUrl);

        } catch (ApiErrorException $e) {
            $this->fail('Stripe API threw an error: ' . $e->getMessage());
        }
    }
}
