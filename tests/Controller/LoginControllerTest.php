<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class LoginControllerTest
 * 
 * This class contains tests for verifying the functionality of the login system,
 * including loading the login page, logging in with valid and invalid credentials,
 * and handling redirections and error messages.
 */
class LoginControllerTest extends WebTestCase
{
    /**
     * Test if the login page loads successfully.
     *
     * @return void
     */
    public function testLoginPageLoads(): void
    {
        $client = static::createClient();

        // Access the login page
        $crawler = $client->request('GET', '/login');

        // Assert that the login page loads correctly
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Veuillez vous connecter');
    }

    /**
     * Test logging in with valid credentials.
     *
     * This test simulates a user submitting valid login credentials,
     * verifies redirection to the homepage, and checks if the user is successfully logged in.
     *
     * @return void
     */
    public function testLoginWithValidCredentials(): void
    {
        $client = static::createClient();

        // Submit the login form with valid credentials
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form([
            '_username' => 'fakeuser@fakemail.com', // User's email in the test database
            '_password' => 'userpassword',         // Corresponding password
        ]);

        $client->submit($form);

        // Assert redirection to the homepage after successful login
        $this->assertResponseRedirects('/');
        $client->followRedirect();

        // Assert that the user is logged in by checking for the logout link
        $this->assertSelectorExists('a[href="/logout"]');
    }

    /**
     * Test logging in with invalid credentials.
     *
     * This test simulates a user submitting invalid login credentials,
     * verifies redirection back to the login page, and checks for an error message.
     *
     * @return void
     */
    public function testLoginWithInvalidCredentials(): void
    {
        $client = static::createClient();

        // Submit the login form with invalid credentials
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form([
            '_username' => 'wronguser@example.com',
            '_password' => 'WrongPassword123!',
        ]);

        $client->submit($form);

        // Assert redirection back to the login page
        $this->assertResponseRedirects('/login');
        $client->followRedirect();

        // Assert that an error message is displayed
        $this->assertSelectorTextContains('.alert-danger', 'Invalid credentials.');
    }
}
