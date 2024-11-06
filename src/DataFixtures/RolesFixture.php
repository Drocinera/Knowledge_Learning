<?php

// src/DataFixtures/RolesFixture.php
namespace App\DataFixtures;

use App\Entity\Role;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RolesFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $role1 = new Role();
        $role1->setName("ROLE_ADMIN");
        $role1->setDescription("Administrator role");
        $manager->persist($role1);

        $role2 = new Role();
        $role2->setName("ROLE_USER");
        $role2->setDescription("Regular user role");
        $manager->persist($role2);

        $manager->flush();
    }
}

