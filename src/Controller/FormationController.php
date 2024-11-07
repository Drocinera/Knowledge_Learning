<?php

namespace App\Controller;

use App\Repository\ThemesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class FormationController extends AbstractController
{
    #[Route('/formation', name: 'app_formation')]
    public function index(): Response
    {
        return $this->render('formation/index.html.twig', [
            'controller_name' => 'FormationController',
        ]);
    }

    #[Route('/formation/{id}', name: 'app_formation_show')]
    public function show(int $id, ThemesRepository $themesRepository): Response
    {
        $formation = $themesRepository->find($id);

        return $this->render('formation/show.html.twig', [
            'formation' => $formation,
        ]);
    }
}
