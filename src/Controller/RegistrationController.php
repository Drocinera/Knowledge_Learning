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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

/**
 * RegistrationController
 */
class RegistrationController extends AbstractController
{
    public function __construct(private EmailVerifier $emailVerifier)
    {
    }

    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        EntityManagerInterface $entityManager,
        Security $security
    ): Response {

        $user = new Users();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $plainPassword = $form->get('plainPassword')->getData();

            $user->setPassword(
                $userPasswordHasher->hashPassword($user, $plainPassword)
            );

            $role = $entityManager->getRepository(Role::class)
                ->findOneBy(['name' => 'ROLE_USER']);

            if (!$role) {
                throw new \LogicException('ROLE_USER must exist in database.');
            }

            $user->setRole($role);

            $entityManager->persist($user);
            $entityManager->flush();

            // Email confirmation
            $this->emailVerifier->sendEmailConfirmation(
                'app_verify_email',
                $user,
                (new TemplatedEmail())
                    ->from(new Address('pichonneautheo@gmail.com', 'Email Confirmation Bot'))
                    ->to((string) $user->getUserIdentifier())
                    ->subject('Veuillez confirmer votre adresse mail')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );

            $this->addFlash('success', 'Un email de vérification a été envoyé.');

            // Option simple (form_login) : on connecte l’utilisateur sans authenticator custom
            return $security->login($user, 'form_login', 'main');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(
        Request $request,
        TranslatorInterface $translator
    ): Response {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        try {
            /** @var Users $user */
            $user = $this->getUser();
            $this->emailVerifier->handleEmailConfirmation($request, $user);

        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash(
                'verify_email_error',
                $translator->trans($exception->getReason(), [], 'VerifyEmailBundle')
            );

            return $this->redirectToRoute('app_login');
        }

        $this->addFlash('success', 'Votre adresse email a bien été vérifiée.');

        return $this->redirectToRoute('app_home');
    }
}