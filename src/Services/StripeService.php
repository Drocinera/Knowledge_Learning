<?php

namespace App\Services;

use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Service for handling Stripe payment operations.
 */
class StripeService
{
    /**
     * @var string $stripeSecretKey The secret API key for Stripe.
     */

    private string $stripeSecretKey;

    /**
     * Constructor.
     *
     * @param ParameterBagInterface $parameterBag Provides access to application parameters.
     */

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->stripeSecretKey = $parameterBag->get('stripe_secret_key');
        Stripe::setApiKey($this->stripeSecretKey);
    }

    /**
     * Creates a new Stripe Checkout session.
     *
     * @param array $items An array of items to be purchased. Each item must include keys like 'price_data', 'quantity', etc.
     * @param string $successUrl The URL to redirect to upon successful payment.
     * @param string $cancelUrl The URL to redirect to if the payment is canceled.
     * @param array $metadata Optional metadata to attach to the checkout session.
     *
     * @return Session The created Stripe Checkout session.
     *
     * @throws \Stripe\Exception\ApiErrorException If an error occurs while creating the session.
     */
    
    public function createCheckoutSession(array $items, string $successUrl, string $cancelUrl, array $metadata = []): Session
    {
        return Session::create([
            'payment_method_types' => ['card'],
            'line_items' => $items,
            'mode' => 'payment',
            'success_url' => $successUrl,
            'cancel_url' => $cancelUrl,
            'metadata' => array_map('strval', $metadata),
        ]);
    }
}
