<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * ConditionUseController
 *
 * This controller manages condition use page
 */
class ConditionUseController extends AbstractController
{

    /**
     * Renders the main condition use page.
     *
     * @return Response A response object that redirects or renders a template.
     */

    #[Route('/condition/use', name: 'app_condition_use')]
    public function index(): Response
    {
        return $this->render('condition_use/index.html.twig', [
            'controller_name' => 'ConditionUseController',
        ]);
    }
}
