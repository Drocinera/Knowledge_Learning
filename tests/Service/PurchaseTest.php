<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use App\Entity\Users;
use App\Entity\Role;
use App\Entity\Courses;
use App\Entity\Themes;
use App\Repository\ThemesRepository;
use App\Repository\CoursesRepository;
use App\Repository\LessonsRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Services\StripeService;
use App\Controller\StripeController;

class PurchaseTest extends WebTestCase
{
    private $client;
    private $session;
    private $container;
    private $entityManager; // Define the property

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->client->enableProfiler();

        $this->container = static::getContainer();

        // Mock the RequestStack and Session
        $requestStack = $this->createMock(RequestStack::class);
        $this->session = $this->createMock(SessionInterface::class);

        // Simulate session being started and available
        $requestStack->method('getSession')->willReturn($this->session);

        // Initialize the EntityManager
        $this->entityManager = $this->container->get(EntityManagerInterface::class);
    }

    /*public function testCheckoutCourse()
    {
        // Prepare a user and a course
        $role = new Role();
        $role->setName('ROLE_USER');
        $role->setDescription('Basic User Role');

        $user = new Users();
        $user->setEmail('testpurchase@example.com');
        $user->setPassword('password');
        $user->setRole($role);
        $user->setCreatedBy('Test');
        $user->setUpdatedBy('Test');

        $theme = new Themes();
        $theme->setName('Test Theme');
        $theme->setDescription('Theme Description');
        $theme->setCreatedBy('Test');
        $theme->setUpdatedBy('Test');

        $course = new Courses();
        $course->setName('Test Course');
        $course->setTheme($theme);
        $course->setPrice('99.99');
        $course->setDescription('Test Course Description');
        $course->setCreatedBy('test');
        $course->setUpdatedBy('test');

        // Persist entities using the EntityManager
        $this->entityManager->persist($role);
        $this->entityManager->persist($user);
        $this->entityManager->persist($theme);
        $this->entityManager->persist($course);
        $this->entityManager->flush();

        $this->assertEquals('testpurchase@example.com', $user->getEmail());

        // Mock the authentication token
        $token = new UsernamePasswordToken($user, 'main', $user->getRoles());
        $this->session->expects($this->once())->method('set')->with('_security_main', serialize($token));
        $this->session->expects($this->once())->method('save');
        $this->container->get('security.token_storage')->setToken($token);

        // Simulate a request to checkout the course
        $this->client->request('GET', '/checkout/course/' . $course->getId());

        // Verify that the response redirects to Stripe
        $this->assertResponseRedirects();
        $responseLocation = $this->client->getResponse()->headers->get('Location');
        $this->assertStringContainsString('checkout.stripe.com', $responseLocation);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();
        $this->entityManager = null; // Avoid memory leaks
    }*/
    public function testCheckoutCourse()
{
    // Mocker les dépendances
    $coursesRepository = $this->createMock(CoursesRepository::class);
    $lessonsRepository = $this->createMock(LessonsRepository::class);
    $stripeService = $this->createMock(StripeService::class);
    $session = $this->createMock(SessionInterface::class);

    $role = new Role();
    $role->setName('ROLE_USER');
    $role->setDescription('Basic User Role');

    $theme = new Themes();
    $theme->setName('Test Theme');
    $theme->setDescription('Theme Description');
    $theme->setCreatedBy('Test');
    $theme->setUpdatedBy('Test');

    // Simuler un cours
    $course = new Courses();
    $course->setName('Test Course');
    $course->setTheme($theme);
    $course->setPrice(99.99);
    $course->setDescription('Test Course Description');
    $course->setCreatedBy('test');
    $course->setUpdatedBy('test');

    // Configurer le mock du repository
    $coursesRepository->method('find')->willReturn($course);

    // Simuler une session Stripe valide
    $stripeSession = $this->createMock(\Stripe\Checkout\Session::class);
    $stripeSession->url = 'https://checkout.stripe.com/session/test';
    $stripeService->method('createCheckoutSession')->willReturn($stripeSession);

    // Simuler un utilisateur authentifié
    $user = new Users();
    $user->setEmail('testpurchase@example.com');
    $user->setPassword('password');
    $user->setRole($role);
    $user->setCreatedBy('Test');
    $user->setUpdatedBy('Test');

    $this->loginUser($user); // Utilisez la méthode personnalisée pour définir l'utilisateur

    // Appeler le contrôleur via une requête HTTP
    $this->client->request('GET', '/checkout/course/1'); // Remplacer par l'URL réelle de votre contrôleur

    // Vérifier la réponse
    $this->assertResponseRedirects();
    $this->assertStringContainsString('https://checkout.stripe.com/session/test', $this->client->getResponse()->headers->get('Location'));
}

private function loginUser(Users $user): void
{
    // Simuler l'authentification
    $token = new UsernamePasswordToken($user, 'main', $user->getRoles());
    $this->container->get('security.token_storage')->setToken($token);

    // Stocker le token dans la session
    $this->session->set('_security_main', serialize($token));
    $this->session->save();
}

}
