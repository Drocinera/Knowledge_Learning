<?php

namespace App\Services;

use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class StripeService
{
    private string $stripeSecretKey;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->stripeSecretKey = $parameterBag->get('stripe_secret_key');
        Stripe::setApiKey($this->stripeSecretKey);

    }

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
