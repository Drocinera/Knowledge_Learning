<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * LoginController
 *
 * This controller manages Login page and logout.
 */
class LoginController extends AbstractController
{

    /**
     * Renders the main login page.
     *
     * @param AuthenticationUtils $authenticationUtils the symfony component for authentification.
     * 
     * @return Response A response object that redirects or renders a template
     */

    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('login/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);

        return $this->redirectToRoute('app_home');
    }

    /**
     * Logout the user.
     *
     * @return Response logout
     * 
     * @throws \Symfony\Component\HttpKernel\Exception\LogicException If the lesson is not found.
     */

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
