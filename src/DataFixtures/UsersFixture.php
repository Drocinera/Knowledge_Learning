<?php

// src/DataFixtures/UsersFixture.php
namespace App\DataFixtures;

use App\Entity\Users;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;



class UsersFixture extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $admin = new Users();
        $admin->setName('Admin');
        $admin->setEmail('admin@example.com');
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'adminpassword'));
        $admin->setRole('ROLE_ADMIN');
        $admin->setIsActive(true);
        $manager->persist($admin);

        $User1 = new Users();
        $User1->setName('User1');
        $User1->setEmail('user@example.com');
        $User1->setPassword($this->passwordHasher->hashPassword($User1, 'User1Password'));
        $User1->setRole('ROLE_USER');
        $User1->setIsActive(true);
        $manager->persist($User1);
        
        $manager->flush();
    }
}
