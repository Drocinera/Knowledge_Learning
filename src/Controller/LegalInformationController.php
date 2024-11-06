<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LegalInformationController extends AbstractController
{
    #[Route('/legal/information', name: 'app_legal_information')]
    public function index(): Response
    {
        return $this->render('legal_information/index.html.twig', [
            'controller_name' => 'LegalInformationController',
        ]);
    }
}
