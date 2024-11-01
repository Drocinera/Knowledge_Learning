<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ThemeController extends AbstractController
{
    #[Route('/theme', name: 'app_theme')]
    public function index(): Response
    {
        return $this->render('theme/index.html.twig', [
            'controller_name' => 'ThemeController',
        ]);
    }
}
