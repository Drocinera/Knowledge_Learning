<?php

namespace App\Tests\Controller;

use App\Entity\Users;
use App\Repository\ThemesRepository;
use App\Repository\CoursesRepository;
use App\Repository\LessonsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

class UserCreationTest extends WebTestCase
{
    protected static function getKernelClass(): string
    {
        return \App\Kernel::class;
    }

    /**
 * @runInSeparateProcess
 */
public function testRegister()
{
    // Create the client and get the container
    $client = static::createClient();
    $container = static::getContainer();

    // Mock dependencies (same as before)
    $passwordHasherMock = $this->createMock(UserPasswordHasherInterface::class);
    $passwordHasherMock->method('hashPassword')
        ->willReturn('hashedPassword123');

        // Mock the CSRF Token Manager
        $csrfTokenManagerMock = $this->createMock(CsrfTokenManagerInterface::class);
        $csrfTokenManagerMock->method('getToken')
            ->willReturn(new CsrfToken('registration_form', 'mocked-token-value'));

    // Set the mock into the container
    $container->set(\Symfony\Component\Security\Csrf\CsrfTokenManagerInterface::class, $csrfTokenManagerMock);
    
    // Mock other repositories (Users, Courses, Lessons, Themes)
    $repositoryMock = $this->createMock(EntityRepository::class);
    $repositoryMock->method('findOneBy')->willReturn(null);

    $themesRepositoryMock = $this->createMock(ThemesRepository::class);
    $themesRepositoryMock->method('findAllFormations')->willReturn([]);
    $coursesRepositoryMock = $this->createMock(CoursesRepository::class);
    $coursesRepositoryMock->method('findAllCourses')->willReturn([]);
    $lessonsRepositoryMock = $this->createMock(LessonsRepository::class);
    $lessonsRepositoryMock->method('findAllLessons')->willReturn([]);

    $container->set(ThemesRepository::class, $themesRepositoryMock);
    $container->set(CoursesRepository::class, $coursesRepositoryMock);
    $container->set(LessonsRepository::class, $lessonsRepositoryMock);

    // Mock the EntityManager
    $entityManagerMock = $this->createMock(EntityManagerInterface::class);
    $entityManagerMock->method('getRepository')->willReturn($repositoryMock);
    $entityManagerMock->expects($this->once())
        ->method('persist')
        ->with($this->isInstanceOf(Users::class));
    $entityManagerMock->expects($this->once())->method('flush');
    $container->set(EntityManagerInterface::class, $entityManagerMock);

    // Access the registration page
    $crawler = $client->request('GET', '/register');
    $this->assertResponseIsSuccessful();
    $this->assertSelectorTextContains('h1', 'Inscription');

    // Debug: Output the HTML to verify structure
    echo $crawler->html();

    $csrfToken = $crawler->filter('input[name="registration_form[_token]"]')->attr('value');

    // Submit the registration form with the correct values
    $form = $crawler->selectButton("S'inscrire")->form([
        'registration_form[email]' => 'testuser@example.com',
        'registration_form[plainPassword][first]' => 'Testpassword123',
        'registration_form[plainPassword][second]' => 'Testpassword123',
        'registration_form[agreeTerms]' => true,
        'registration_form[_token]' => $csrfToken,
    ]);

    // Submit the form
    $client->submit($form);

    // Assert the redirection
    $this->assertResponseRedirects('/home');

    $client->getContainer()->get('session')->clear();

}

}
