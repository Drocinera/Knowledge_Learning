<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SiteMapController extends AbstractController
{
    #[Route('/site/map', name: 'app_site_map')]
    public function index(): Response
    {
        return $this->render('site_map/index.html.twig', [
            'controller_name' => 'SiteMapController',
        ]);
    }
}
