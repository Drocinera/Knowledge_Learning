<?php

namespace App\Tests\Repository;

use App\Entity\Users;
use App\Entity\Role;
use App\Repository\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UsersRepositoryTest extends KernelTestCase
{
    private $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()
                                      ->get('doctrine')
                                      ->getManager();
    }

    public function testFindUserByEmail()
    {
        // Récupérer le container pour les services
        $container = static::getContainer();

        // Récupérer le UsersRepository
        $roleRepository = $container->get('doctrine')->getRepository(Role::class);

        $role = $roleRepository->findOneBy(['name' => 'ROLE_USER']);
    if (!$role) {
        $role = new Role();
        $role->setName('ROLE_USER');
        $this->entityManager->persist($role);
        $this->entityManager->flush();
    }

        // Créer un utilisateur pour le test
        $user = new Users();
        $user->setEmail('testuser@example.com');
        $user->setPassword('hashedpassword');
        $user->setRole($role);
        $user->setCreatedBy('Test');
        $user->setUpdatedBy('Test');

        // Persister les données en base
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $repository = $container->get(UsersRepository::class);

        // Vérifier que l'utilisateur peut être récupéré via le repository
        $foundUser = $repository->findOneBy(['email' => 'testuser@example.com']);
        $this->assertNotNull($foundUser);
        $this->assertEquals('testuser@example.com', $foundUser->getEmail());
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();
        $this->entityManager = null; // Éviter les problèmes de mémoire
    }
}
