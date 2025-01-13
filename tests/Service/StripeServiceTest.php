<?php

namespace App\Tests\Services;

use App\Services\StripeService;
use PHPUnit\Framework\TestCase;
use Stripe\Checkout\Session;
use Stripe\Exception\ApiErrorException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class StripeServiceTest
 *
 * This class contains unit tests for the StripeService class, focusing on
 * the `createCheckoutSession` method.
 */
class StripeServiceTest extends TestCase
{
    /**
     * @var StripeService $stripeService
     * The instance of StripeService being tested.
     */
    private $stripeService;

    /**
     * Sets up the test environment by initializing a mock of the ParameterBagInterface
     * and injecting it into an instance of StripeService.
     *
     * @return void
     */
    protected function setUp(): void
    {
        // Retrieve the Stripe secret key from the environment or use a test value
        $stripeSecretKey = getenv('STRIPE_SECRET_KEY') ?: 'test_secret_key';

        // Mock the ParameterBagInterface to simulate fetching configuration parameters
        $parameterBagMock = $this->createMock(ParameterBagInterface::class);
        $parameterBagMock->method('get')
            ->with('stripe_secret_key')
            ->willReturn($stripeSecretKey);

        // Initialize the StripeService with the mock parameter bag
        $this->stripeService = new StripeService($parameterBagMock);
    }

    /**
     * Tests the `createCheckoutSession` method of the StripeService.
     *
     * This test verifies that a checkout session can be created with valid inputs,
     * and that the returned session has the expected success and cancel URLs.
     *
     * @return void
     */
    public function testCreateCheckoutSession(): void
    {
        // Define the items to include in the Stripe checkout session
        $items = [
            [
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => ['name' => 'Test Product'],
                    'unit_amount' => 5000, // 50 EUR in cents
                ],
                'quantity' => 1,
            ],
        ];

        // Define success and cancel URLs for the session
        $successUrl = 'https://example.com/success';
        $cancelUrl = 'https://example.com/cancel';

        try {
            // Attempt to create a checkout session
            $session = $this->stripeService->createCheckoutSession($items, $successUrl, $cancelUrl);

            // Assert that the session is an instance of the expected class
            $this->assertInstanceOf(Session::class, $session, 'The session should be an instance of Stripe\\Checkout\\Session.');

            // Assert that the success and cancel URLs match the expected values
            $this->assertEquals($successUrl, $session->success_url, 'The success URL should match the input URL.');
            $this->assertEquals($cancelUrl, $session->cancel_url, 'The cancel URL should match the input URL.');

        } catch (ApiErrorException $e) {
            // Fail the test if an API error occurs
            $this->fail('Stripe API threw an error: ' . $e->getMessage());
        }
    }
}
