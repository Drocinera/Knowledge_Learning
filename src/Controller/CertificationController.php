<?php

namespace App\Controller;

use App\Repository\LessonsRepository;
use App\Repository\CertificationsRepository;

use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * CertificationController
 *
 * This controller manages certification 
 */
class CertificationController extends AbstractController
{

    /**
     * Renders the main certificationdmin page .
     *
     * @return Response A response object that redirects or renders a template.
     * 
     */

    #[Route('/certifications', name: 'app_certifications')]
public function certifications(): Response
{
    $user = $this->getUser();
    if (!$user) {
        return $this->redirectToRoute('app_login');
    }

    return $this->render('certifications/index.html.twig', [
        'certifications' => $user->getCertifications(),
    ]);
}
}
