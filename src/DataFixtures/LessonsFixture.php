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
            ['name' => 'Découverte de l’instrument', 'price' => 26, 'course' => 'Cursus d’initiation à la guitare', 'video_url' =>''],
            ['name' => 'Les accords et les gammes', 'price' => 26, 'course' => 'Cursus d’initiation à la guitare', 'video_url' =>''],
            ['name' => 'Découverte de l’instrument', 'price' => 26, 'course' => 'Cursus d’initiation au piano', 'video_url' =>''],
            ['name' => 'Les accords et les gammes', 'price' => 26, 'course' => 'Cursus d’initiation au piano', 'video_url' =>''],
            ['name' => 'Les langages Html et CSS', 'price' => 32, 'course' => 'Cursus d’initiation au développement web', 'video_url' =>'Videos/Video_lecon_langage_html_css.mp4'],
            ['name' => 'Dynamiser votre site avec Javascript', 'price' => 32, 'course' => 'Cursus d’initiation au développement web', 'video_url' =>''],
            ['name' => 'Les outils du jardinier', 'price' => 16, 'course' => 'Cursus d’initiation au jardinage', 'video_url' =>''],
            ['name' => 'Jardiner avec la lune', 'price' => 16, 'course' => 'Cursus d’initiation au jardinage', 'video_url' =>''],
            ['name' => 'Les modes de cuisson', 'price' => 23, 'course' => 'Cursus d’initiation à la cuisine', 'video_url' =>''],
            ['name' => 'Les saveurs', 'price' => 23, 'course' => 'Cursus d’initiation à la cuisine', 'video_url' =>''],
            ['name' => 'Mettre en œuvre le style dans l’assiette', 'price' => 26, 'course' => 'Cursus d’initiation à l’art du dressage culinaire', 'video_url' =>'Videos/Video_mettre_en_œuvre_le_style _dans_assiette.mp4'],
            ['name' => 'Harmoniser un repas à quatre plats', 'price' => 26, 'course' => 'Cursus d’initiation à l’art du dressage culinaire', 'video_url' =>''],
        ];

        foreach ($lessonsData as $lessonData) {
            $lesson = new Lessons();
            $lesson->setName($lessonData['name']);
            $lesson->setPrice($lessonData['price']);
            $lesson->setDescription($lessonData['name']);
            $lesson->setvideo_url($lessonData['video_url']);
            $theme->setCreatedBy(1);
            $theme->setUpdatedBy(1);

            // Associe le cursus
            $lesson->setCourse($this->getReference($lessonData['course']));
            
            $manager->persist($lesson);
        }

        $manager->flush();
    }
}
