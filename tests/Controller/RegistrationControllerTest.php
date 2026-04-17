<?php

namespace App\Tests\Controller;

use App\Entity\Users;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class RegistrationControllerTest
 */
class RegistrationControllerTest extends WebTestCase
{
    public function testRegister(): void
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/register');
        $this->assertResponseIsSuccessful();

        // ⚠️ Plus besoin de récupérer manuellement le CSRF token
        $form = $crawler->selectButton("S'inscrire")->form([
            'registration_form[email]' => 'test@example.com',
            'registration_form[plainPassword][first]' => 'Test123@',
            'registration_form[plainPassword][second]' => 'Test123@',
            'registration_form[agreeTerms]' => true,
        ]);

        $client->submit($form);

        // ⚠️ suivre la redirection pour éviter certains comportements dépréciés
        $this->assertResponseRedirects('/');
        $client->followRedirect();

        $container = static::getContainer();

        // ⚠️ getDoctrine() est déprécié → utiliser ManagerRegistry
        $entityManager = $container->get('doctrine')->getManager();
        $userRepository = $entityManager->getRepository(Users::class);

        $user = $userRepository->findOneBy(['email' => 'test@example.com']);

        $this->assertNotNull($user);

        // ⚠️ ancien service déprécié
        $passwordHasher = $container->get('security.user_password_hasher');

        $this->assertTrue(
            $passwordHasher->isPasswordValid($user, 'Test123@')
        );

        // ⚠️ dépend de ton implémentation Users::getRoles()
        $this->assertContains('ROLE_USER', $user->getRoles());

        // ⚠️ vérifier que le profiler est activé
        $profile = $client->getProfile();

        if ($profile) {
            $mailerCollector = $profile->getCollector('mailer');

            $this->assertSame(1, $mailerCollector->getMessageCount());

            $email = $mailerCollector->getMessages()[0];

            $this->assertEmailHeaderSame($email, 'to', 'test@example.com');
            $this->assertEmailHeaderSame($email, 'subject', 'Veuillez confirmer votre adresse mail');
        }
    }
}