<?php

// src/DataFixtures/ThemeFixture.php
namespace App\DataFixtures;

use App\Entity\Themes;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;



class ThemesFixture extends Fixture
{
    public const THEMES = [
        'Musique' => 'Thème autour de la musique et des instruments',
        'Informatique' => 'Introduction aux concepts du développement et de l\'informatique',
        'Jardinage' => 'Bases et techniques du jardinage',
        'Cuisine' => 'Initiation à la cuisine et à l\'art culinaire',
    ];

    public function load(ObjectManager $manager): void
    {

        foreach (self::THEMES as $name => $description) {
            $theme = new Themes();
            $theme->setName($name);
            $theme->setDescription($description);
            $theme->setCreatedBy(1);
            $theme->setUpdatedBy(1);

            $manager->persist($theme);
            $this->addReference($name, $theme);
        }
        
        $manager->flush();
    }
}

