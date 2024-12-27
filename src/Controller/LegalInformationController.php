<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * LegalInformationController
 *
 * This controller manages legal information page
 */
class LegalInformationController extends AbstractController
{

    /**
     * Renders the main legal information page.
     *
     * @return Response A response object that redirects or renders a template.
     */

    #[Route('/legal/information', name: 'app_legal_information')]
    public function index(): Response
    {
        return $this->render('legal_information/index.html.twig', [
            'controller_name' => 'LegalInformationController',
        ]);
    }
}
