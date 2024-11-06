<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ConditionUseController extends AbstractController
{
    #[Route('/condition/use', name: 'app_condition_use')]
    public function index(): Response
    {
        return $this->render('condition_use/index.html.twig', [
            'controller_name' => 'ConditionUseController',
        ]);
    }
}
