<?php

namespace App\Tests\Repository;

use App\Entity\Users;
use App\Entity\Role;
use App\Repository\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class UsersRepositoryTest
 *
 * This class contains tests for the UsersRepository to verify its behavior,
 * including user retrieval by email and database persistence of entities.
 */
class UsersRepositoryTest extends KernelTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManagerInterface|null $entityManager
     * The Entity Manager used for database operations in the test environment.
     */
    private $entityManager;

    /**
     * Sets up the test environment by booting the Symfony kernel and initializing
     * the Entity Manager for database operations.
     *
     * @return void
     */
    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()
                                      ->get('doctrine')
                                      ->getManager();
    }

    /**
     * Test retrieving a user by email from the UsersRepository.
     *
     * This test creates a user in the database, retrieves it using the repository,
     * and verifies the retrieved user's email matches the created user's email.
     *
     * @return void
     */
    public function testFindUserByEmail(): void
    {
        // Get the container for accessing services
        $container = static::getContainer();

        // Retrieve or create the ROLE_USER role
        $roleRepository = $container->get('doctrine')->getRepository(Role::class);
        $role = $roleRepository->findOneBy(['name' => 'ROLE_USER']);
        if (!$role) {
            $role = new Role();
            $role->setName('ROLE_USER');
            $this->entityManager->persist($role);
            $this->entityManager->flush();
        }

        // Create a user entity for the test
        $user = new Users();
        $user->setEmail('testuser@example.com');
        $user->setPassword('hashedpassword');
        $user->setRole($role);
        $user->setCreatedBy('Test');
        $user->setUpdatedBy('Test');

        // Persist the user entity to the database
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        // Retrieve the user using the repository
        $repository = $container->get(UsersRepository::class);
        $foundUser = $repository->findOneBy(['email' => 'testuser@example.com']);

        // Assert that the user is found and its email matches
        $this->assertNotNull($foundUser, 'User should be found in the database.');
        $this->assertEquals('testuser@example.com', $foundUser->getEmail(), 'The retrieved email should match.');
    }

    /**
     * Tears down the test environment by closing the Entity Manager
     * and freeing up memory.
     *
     * @return void
     */
    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();
        $this->entityManager = null; // Avoid memory leaks
    }
}
