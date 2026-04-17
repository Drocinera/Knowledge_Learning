<?php

namespace App\Tests\Repository;

use App\Entity\Users;
use App\Entity\Role;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UsersRepositoryTest extends KernelTestCase
{
    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        self::bootKernel();

        // ✅ Remplacement de get('doctrine')
        $container = static::getContainer();
        $this->entityManager = $container
            ->get(ManagerRegistry::class)
            ->getManager();
    }

    public function testFindUserByEmail(): void
    {
        $container = static::getContainer();

        // ✅ utiliser ManagerRegistry aussi ici
        $roleRepository = $this->entityManager->getRepository(Role::class);

        $role = $roleRepository->findOneBy(['name' => 'ROLE_USER']);

        if (!$role) {
            $role = new Role();
            $role->setName('ROLE_USER');

            $this->entityManager->persist($role);
            $this->entityManager->flush();
        }

        $user = new Users();
        $user->setEmail('testuser@example.com');
        $user->setPassword('hashedpassword');
        $user->setRole($role);
        $user->setCreatedBy('Test');
        $user->setUpdatedBy('Test');

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        // ✅ récupération propre du repository
        $repository = $container->get(UsersRepository::class);

        $foundUser = $repository->findOneBy([
            'email' => 'testuser@example.com'
        ]);

        $this->assertNotNull($foundUser);
        $this->assertSame('testuser@example.com', $foundUser->getEmail());
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // ✅ bonne pratique Doctrine
        if ($this->entityManager->isOpen()) {
            $this->entityManager->close();
        }

        $this->entityManager = null;
    }
}