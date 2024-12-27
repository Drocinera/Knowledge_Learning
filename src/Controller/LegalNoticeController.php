<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * LegalNoticeController
 *
 * This controller manages legal notice page
 */
class LegalNoticeController extends AbstractController
{

    /**
     * Renders the main legal notice page.
     *
     * @return Response A response object that redirects or renders a template.
     */

    #[Route('/legal/notice', name: 'app_legal_notice')]
    public function index(): Response
    {
        return $this->render('legal_notice/index.html.twig', [
            'controller_name' => 'LegalNoticeController',
        ]);
    }
}
