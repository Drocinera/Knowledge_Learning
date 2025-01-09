<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoginControllerTest extends WebTestCase
{
    public function testLoginPageLoads(): void
    {
        $client = static::createClient();

        // Accéder à la page de connexion
        $crawler = $client->request('GET', '/login');

        // Vérifier que la page de connexion se charge correctement
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Veuillez vous connecter');
    }

    public function testLoginWithValidCredentials(): void
    {
        $client = static::createClient();

        // Soumettre le formulaire avec des identifiants valides
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form([
            '_username' => 'fakeuser@fakemail.com', // Email de l'utilisateur dans votre base de test
            '_password' => 'userpassword',        // Mot de passe correspondant
        ]);

        $client->submit($form);


        // Vérifier la redirection vers la page d'accueil après connexion réussie
        $this->assertResponseRedirects('/'); 
        $client->followRedirect();

        // Vérifier que l'utilisateur est bien connecté
        $this->assertSelectorExists('a[href="/logout"]'); // Vérifie que le lien de déconnexion est visible
    }

    public function testLoginWithInvalidCredentials(): void
    {
        $client = static::createClient();

        // Soumettre le formulaire avec des identifiants invalides
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form([
            '_username' => 'wronguser@example.com',
            '_password' => 'WrongPassword123!',
        ]);

        $client->submit($form);

        // Vérifier que la connexion échoue, renvoie vers la page de connexion et qu'une erreur est affichée
        $this->assertResponseRedirects('/login');
        $client->followRedirect();

        $this->assertSelectorTextContains('.alert-danger', 'Invalid credentials.');
    }
}
