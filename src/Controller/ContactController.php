<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * ContactController
 *
 * This controller manages contact page
 */
class ContactController extends AbstractController
{

    /**
     * Renders the main contact page.
     *
     * @return Response A response object that redirects or renders a template.
     */

    #[Route('/contact', name: 'app_contact')]
    public function index(): Response
    {
        return $this->render('contact/index.html.twig', [
            'controller_name' => 'ContactController',
        ]);
    }
}
