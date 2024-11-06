<?php

// src/DataFixtures/CoursesFixture.php
namespace App\DataFixtures;

use App\Entity\Courses;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CoursesFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        
        $coursesData = [
            ['name' => 'Cursus d’initiation à la guitare', 'price' => 50, 'description' => 'Apprendre les bases de la guitare', 'theme' => 'Musique'],
            ['name' => 'Cursus d’initiation au piano', 'price' => 50, 'description' => 'Découverte du piano et de ses bases', 'theme' => 'Musique'],
            ['name' => 'Cursus d’initiation au développement web', 'price' => 60, 'description' => 'Apprendre les bases du développement web', 'theme' => 'Informatique'],
            ['name' => 'Cursus d’initiation au jardinage', 'price' => 30, 'description' => 'Découverte des outils et techniques du jardinage', 'theme' => 'Jardinage'],
            ['name' => 'Cursus d’initiation à la cuisine', 'price' => 44, 'description' => 'Découvrir les bases de la cuisine', 'theme' => 'Cuisine'],
            ['name' => 'Cursus d’initiation à l’art du dressage culinaire', 'price' => 48, 'description' => 'Apprendre l\'art du dressage culinaire', 'theme' => 'Cuisine'],
        ];

        foreach ($coursesData as $courseData) {
            $course = new Courses();
            $course->setName($courseData['name']);
            $course->setPrice($courseData['price']);
            $course->setDescription($courseData['description']);
            $theme->setCreatedBy(1);
            $theme->setUpdatedBy(1);

            // Associe le thème
            $course->setTheme($this->getReference($courseData['theme']));
            
            $manager->persist($course);
            $this->addReference($courseData['name'], $course);
        }

        $manager->flush();
    }
}

