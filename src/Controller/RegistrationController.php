<?php

namespace App\Controller;

use App\Entity\Users;
use App\Entity\Role;

use App\Form\RegistrationFormType;

use App\Security\EmailVerifier;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * RegistatrionController
 *
 * This controller manages registration page and verify email.
 */
class RegistrationController extends AbstractController
{
    public function __construct(private EmailVerifier $emailVerifier)
    {
    }

    /**
     * Renders the main register page and send a confirmation email after sign-in.
     * @param Request $request The HTTP request object containing user data.
     * @param EntityManagerInterface $entityManager The Doctrine EntityManager for database operations.
     * @param UserPasswordHasherInterface $userPasswordHasher The symfony component for password hashing.
     * @param Security $security The bundle symfony for security for register, login and other.
     * 
     * @return Response A response object that redirects or renders a template
     * 
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If the role does not exist.
     */

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, Security $security, EntityManagerInterface $entityManager): Response
    {
        $user = new Users();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var string $plainPassword */
            $plainPassword = $form->get('plainPassword')->getData();

            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));
            $roleRepository = $entityManager->getRepository(Role::class);
            $role = $roleRepository->findOneBy(['name' => 'ROLE_USER']);

            if ($role) {
                $user->setRole($role);
            } else {
                throw new \Exception("Role not found");
            }
            $entityManager->persist($user);
            $entityManager->flush();

            // generate a signed url and email it to the user
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('pichonneautheo@gmail.com:', 'Email Confirmation Bot'))
                    ->to((string) $user->getUserIdentifier())
                    ->subject('Veuillez confirmer votre adresse mail')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );

            return $security->login($user, 'form_login', 'main');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * Function for verify email.
     * @param Request $request The HTTP request object containing user data.
     * @param TranslatorInterface $translator The symfony contract for auto translate
     * 
     * @return Response A response object that redirects or renders a template
     * 
     * @throws \Symfony\Component\HttpKernel\Exception\VerifyEmailExceptionInterface If the email verifier  have a problem.
     */

    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, TranslatorInterface $translator): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // validate email confirmation link, sets User::isActive=true and persists
        try {
            /** @var Users $user */
            $user = $this->getUser();
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

            return $this->redirectToRoute('app_login');
        }
        
        $this->addFlash('success', 'Votre adresse email a bien été vérifiée.');

        return $this->redirectToRoute('app_home');
    }
}
