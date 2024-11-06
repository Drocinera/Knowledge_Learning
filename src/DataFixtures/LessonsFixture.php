<?php

// src/DataFixtures/LessonFixture.php
namespace App\DataFixtures;

use App\Entity\Lessons;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class LessonsFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $lessonsData = [
            ['name' => 'Découverte de l’instrument', 'price' => 26, 'course' => 'Cursus d’initiation à la guitare'],
            ['name' => 'Les accords et les gammes', 'price' => 26, 'course' => 'Cursus d’initiation à la guitare'],
            ['name' => 'Découverte de l’instrument', 'price' => 26, 'course' => 'Cursus d’initiation au piano'],
            ['name' => 'Les accords et les gammes', 'price' => 26, 'course' => 'Cursus d’initiation au piano'],
            ['name' => 'Les langages Html et CSS', 'price' => 32, 'course' => 'Cursus d’initiation au développement web'],
            ['name' => 'Dynamiser votre site avec Javascript', 'price' => 32, 'course' => 'Cursus d’initiation au développement web'],
            ['name' => 'Les outils du jardinier', 'price' => 16, 'course' => 'Cursus d’initiation au jardinage'],
            ['name' => 'Jardiner avec la lune', 'price' => 16, 'course' => 'Cursus d’initiation au jardinage'],
            ['name' => 'Les modes de cuisson', 'price' => 23, 'course' => 'Cursus d’initiation à la cuisine'],
            ['name' => 'Les saveurs', 'price' => 23, 'course' => 'Cursus d’initiation à la cuisine'],
            ['name' => 'Mettre en œuvre le style dans l’assiette', 'price' => 26, 'course' => 'Cursus d’initiation à l’art du dressage culinaire'],
            ['name' => 'Harmoniser un repas à quatre plats', 'price' => 26, 'course' => 'Cursus d’initiation à l’art du dressage culinaire'],
        ];

        foreach ($lessonsData as $lessonData) {
            $lesson = new Lessons();
            $lesson->setName($lessonData['name']);
            $lesson->setPrice($lessonData['price']);
            $lesson->setDescription($lessonData['name']);
            $theme->setCreatedBy(1);
            $theme->setUpdatedBy(1);

            // Associe le cursus
            $lesson->setCourse($this->getReference($lessonData['course']));
            
            $manager->persist($lesson);
        }

        $manager->flush();
    }
}
