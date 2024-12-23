<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserLoginTest extends WebTestCase
{
    public function testLoginSuccess()
    {
        $client = static::createClient();

        // Simuler la soumission du formulaire de connexion avec des informations valides
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form();
        $form['_username'] = 'testuser@example.com';
        $form['_password'] = 'Testpassword123';

        $client->submit($form);

        // Vérifiez que la redirection se fait vers la page d'accueil (ou une page protégée)
        $this->assertResponseRedirects('/');
    }

    public function testLoginFailure()
    {
        $client = static::createClient();

        // Simuler une tentative de connexion avec des informations invalides
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form();
        $form['_username'] = 'wrong@example.com';
        $form['_password'] = 'wrongpassword';
        
        $client->submit($form);

        $this->assertResponseStatusCodeSame(401);

        $this->assertResponseRedirects('/login');

        // Vérifiez que l'erreur de connexion s'affiche
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('.error', 'Invalid credentials.');
    }
}
