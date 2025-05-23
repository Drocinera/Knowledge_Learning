<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * HomeController
 *
 * This controller manages home page 
 */
class HomeController extends AbstractController
{

    /**
     * Renders the main home page.
     *
     * @return Response A response object that redirects or renders a template
     */

    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    /**
     * Display all formations in the database.
     *
     * @return Response A response object that redirects or renders a template
     */

    public function DropdownFormation()
    {
        $formations = $this->getDoctrine()->getRepository(Formation::class)->findAll();

        return $this->render('base.html.twig', [
            'formations' => $formations,
        ]);
    }

}
