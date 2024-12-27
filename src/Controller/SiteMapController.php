<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * SiteMapController
 *
 * This controller manages Site Map page.
 */
class SiteMapController extends AbstractController
{

    /**
     * Renders the main site map page.
     *
     * @return Response A response object that redirects or renders a template.
     */

    #[Route('/site/map', name: 'app_site_map')]
    public function index(): Response
    {
        return $this->render('site_map/index.html.twig', [
            'controller_name' => 'SiteMapController',
        ]);
    }
}
