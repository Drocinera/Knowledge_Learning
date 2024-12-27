<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


/**
 * PrivacyPolicycontroller
 *
 * This controller manages Privacy policy page.
 */
class PrivacyPolicyController extends AbstractController
{

    /**
     * Renders the main privacy policy page.
     * 
     * @return Response A response object that redirects or renders a template.s
     */

    #[Route('/privacy/policy', name: 'app_privacy_policy')]
    public function index(): Response
    {
        return $this->render('privacy_policy/index.html.twig', [
            'controller_name' => 'PrivacyPolicyController',
        ]);
    }
}
