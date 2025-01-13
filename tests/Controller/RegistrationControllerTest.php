<?php

namespace App\Tests\Controller;

use App\Entity\Users;
use App\Entity\Role;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Class RegistrationControllerTest
 *
 * This class contains functional tests for the registration process.
 * It tests the registration form, validates data persistence, and verifies email sending.
 */
class RegistrationControllerTest extends WebTestCase
{
    /**
     * Test the user registration process.
     *
     * This test performs the following steps:
     * - Simulates accessing the registration page.
     * - Submits a registration form with valid data.
     * - Verifies the user is created and persisted in the database.
     * - Checks that an email confirmation is sent.
     *
     * @return void
     */
    public function testRegister(): void
    {
        // Create a test client
        $client = static::createClient();

        // Simulate accessing the registration form page
        $crawler = $client->request('GET', '/register');
        $this->assertResponseIsSuccessful();

        // Retrieve the CSRF token for the registration form
        $csrfToken = static::getContainer()
            ->get('security.csrf.token_manager')
            ->getToken('registration_form')
            ->getValue();

        // Fill and submit the registration form with valid data
        $form = $crawler->selectButton('S\'inscrire')->form([
            'registration_form[email]' => 'test@example.com', // Valid email address
            'registration_form[plainPassword][first]' => 'Test123@', // Valid password
            'registration_form[plainPassword][second]' => 'Test123@', // Password confirmation
            'registration_form[agreeTerms]' => true, // Accept terms and conditions
            'registration_form[_token]' => $csrfToken, // CSRF token
        ]);

        $client->submit($form);

        // Verify redirection to the home page after submission
        $this->assertResponseRedirects('/');

        // Check that the user data is saved in the database
        $userRepository = static::getContainer()->get('doctrine')->getRepository(Users::class);
        $user = $userRepository->findOneBy(['email' => 'test@example.com']);

        $this->assertNotNull($user, 'The user should be created in the database.');
        $this->assertTrue(
            static::getContainer()->get('security.password_hasher')->isPasswordValid($user, 'Test123@'),
            'The password should be hashed correctly.'
        );
        $this->assertContains('ROLE_USER', $user->getRoles(), 'The user role should include ROLE_USER.');

        // Verify that a confirmation email is sent
        $profile = $client->getProfile();
        $mailerCollector = $profile->getCollector('mailer');
        $this->assertSame(1, $mailerCollector->getMessageCount(), 'A confirmation email should be sent.');

        // Check the details of the sent email
        $email = $mailerCollector->getMessages()[0];
        $this->assertEmailHeaderSame($email, 'to', 'test@example.com');
        $this->assertEmailHeaderSame($email, 'subject', 'Veuillez confirmer votre adresse mail');
    }
}
