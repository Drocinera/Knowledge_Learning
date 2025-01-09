<?php

namespace App\Tests\Controller;

use App\Entity\Users;
use App\Entity\Role;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\HttpFoundation\Session\Session;

class RegistrationControllerTest extends WebTestCase
{
    public function testRegister(): void
    {
        // Crée le client de test
        $client = static::createClient();

        // Active le profiler pour avoir accès à la session et aux tokens CSRF
        $client->enableProfiler();

        // Crée une session simulée
        $session = new Session(new MockArraySessionStorage());
        $client->getContainer()->set('session', $session); // Ajoute la session au client de test


        // Récupération des services nécessaires
        $entityManager = self::getContainer()->get(EntityManagerInterface::class);
        $passwordHasher = self::getContainer()->get(UserPasswordHasherInterface::class);

        // Création du rôle ROLE_USER si absent
        $roleRepository = $entityManager->getRepository(Role::class);
        $role = $roleRepository->findOneBy(['name' => 'ROLE_USER']);
        if (!$role) {
            $role = new Role();
            $role->setName('ROLE_USER');
            $entityManager->persist($role);
            $entityManager->flush();
        }

        // Simulation du formulaire d'enregistrement
        $crawler = $client->request('GET', '/register');

        // On récupère le token CSRF du formulaire
        $csrfToken = $client->getContainer()->get('security.csrf.token_manager')->getToken('registration_form')->getValue();

        // Soumission du formulaire avec le token CSRF inclus
        $form = $crawler->selectButton('S\'inscrire')->form([
            'registration_form[email]' => 'test@example.com',
            'registration_form[plainPassword][first]' => 'Test123@',
            'registration_form[plainPassword][second]' => 'Test123@',
            'registration_form[agreeTerms]' => true,
            'registration_form[_token]' => $csrfToken,  // Ajouter le token CSRF ici
        ]);


        // Soumission du formulaire
        $client->submit($form);

        // Vérification de la redirection après soumission
        $this->assertResponseRedirects('/');

        // Vérification des données en base de données
        $userRepository = $entityManager->getRepository(Users::class);
        $user = $userRepository->findOneBy(['email' => 'test@example.com']);

        $this->assertNotNull($user, 'L\'utilisateur doit être créé');
        $this->assertTrue(
            $passwordHasher->isPasswordValid($user, 'Test123@'),
            'Le mot de passe doit être haché correctement'
        );
        $this->assertEquals($role, $user->getRole(), 'Le rôle de l\'utilisateur doit être ROLE_USER');
        $this->assertFalse($user->isActive(), 'L\'utilisateur ne doit pas être actif avant de vérifier l\'email');

        // Vérification de l'envoi de l'email
        $emailCollector = $client->getProfile()->getCollector('mailer');
        $this->assertSame(1, $emailCollector->getMessageCount(), 'Un email doit être envoyé');
        $email = $emailCollector->getMessages()[0];
        $this->assertEmailHeaderSame($email, 'to', 'test@example.com');
        $this->assertEmailHeaderSame($email, 'subject', 'Veuillez confirmer votre adresse mail');
    }
}
