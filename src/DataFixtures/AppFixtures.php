<?php

namespace App\DataFixtures;

use App\Entity\Role;
use App\Entity\Users;
use App\Entity\Themes;
use App\Entity\Courses;
use App\Entity\Lessons;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

public function __construct(UserPasswordHasherInterface $passwordHasher)
{
    $this->passwordHasher = $passwordHasher;
}

    public function load(ObjectManager $manager): void
    {
        // Create Roles
        
        $roleAdmin = new Role();
        $roleAdmin->setName('ROLE_ADMIN');
        $roleAdmin->setDescription('Administrator role with full permissions.');
        $manager->persist($roleAdmin);

        $roleUser = new Role();
        $roleUser->setName('ROLE_USER');
        $roleUser->setDescription('Standard user role with limited permissions.');
        $manager->persist($roleUser);

        // Create Users

        $user1 = new Users();
        $user1->setEmail('fakeadmin@fakemail.com');
        $user1->setPassword($this->passwordHasher->hashPassword($user1, 'adminpassword'));
        $user1->setRole($roleAdmin);
        $user1->setActive(true);
        $user1->setCreatedBy('admin');
        $user1->setUpdatedBy('admin');
        $manager->persist($user1);

        $user2 = new Users();
        $user2->setEmail('fakeuser@fakemail.com');
        $user2->setPassword($this->passwordHasher->hashPassword($user2, 'userpassword'));
        $user2->setRole($roleUser);
        $user2->setActive(true);
        $user2->setCreatedBy('admin');
        $user2->setUpdatedBy('admin');
        $manager->persist($user2);

        // Create themes 

            $theme = new Themes();
            $theme->setName('Musique');
            $theme->setDescription('Cours d\'informatique pour tous les niveaux.');
            $theme->setCreatedBy('admin');
            $theme->setUpdatedBy('admin');
            $theme->setCreatedAt(new DateTimeImmutable());
            $theme->setUpdatedAt(new DateTimeImmutable());
            $theme->setImage('Photo_Musique_Pixabay.jpg');

            $theme2 = new Themes();
            $theme2->setName('Informatique');
            $theme2->setDescription('Cours de cuisine pour tous les niveaux.');
            $theme2->setCreatedBy('admin');
            $theme2->setUpdatedBy('admin');
            $theme2->setCreatedAt(new DateTimeImmutable());
            $theme2->setUpdatedAt(new DateTimeImmutable());
            $theme2->setImage('Photo_Informatique_luis_gomes.jpg');

            $theme3 = new Themes();
            $theme3->setName('Jardinage');
            $theme3->setDescription('Cours d\'informatique pour tous les niveaux.');
            $theme3->setCreatedBy('admin');
            $theme3->setUpdatedBy('admin');
            $theme3->setCreatedAt(new DateTimeImmutable());
            $theme3->setUpdatedAt(new DateTimeImmutable());
            $theme3->setImage('Photo_Jardinage_cottonbro_studio.jpg');

            $theme4 = new Themes();
            $theme4->setName('Cuisine');
            $theme4->setDescription('Cours de cuisine pour tous les niveaux.');
            $theme4->setCreatedBy('admin');
            $theme4->setUpdatedBy('admin');
            $theme3->setCreatedAt(new DateTimeImmutable());
            $theme3->setUpdatedAt(new DateTimeImmutable());
            $theme4->setImage('Photo_Cuisine_Rene_Terp.jpg');

            $manager->persist($theme);
            $manager->persist($theme2);
            $manager->persist($theme3);
            $manager->persist($theme4);
    
        // Create Courses

        $course1 = new Courses();
        $course1->setName('Cursus d’initiation à la guitare');
        $course1->setPrice('50.00');
        $course1->setDescription('Cursus d’initiation à la guitare');
        $course1->setCreatedBy('admin');
        $course1->setUpdatedBy('admin');
        $course1>setCreatedAt(new DateTimeImmutable());
        $course1->setUpdatedAt(new DateTimeImmutable());
        $course1->setTheme($theme1);
        
        $course2 = new Courses();
        $course2->setName('Cursus d’initiation au piano');
        $course2->setPrice('50.00');
        $course2->setDescription('Cursus d’initiation au piano');
        $course2->setCreatedBy('admin');
        $course2->setUpdatedBy('admin');
        $course2>setCreatedAt(new DateTimeImmutable());
        $course2->setUpdatedAt(new DateTimeImmutable());
        $course2->setTheme($theme1);

        $course3 = new Courses();
        $course3->setName('Cursus d’initiation au développement web');
        $course3->setPrice('60.00');
        $course3->setDescription('Cursus d’initiation au développement web');
        $course3->setCreatedBy('admin');
        $course3->setUpdatedBy('admin');
        $course3->setCreatedAt(new DateTimeImmutable());
        $course3->setUpdatedAt(new DateTimeImmutable());
        $course3->setTheme($theme2);

        $course4 = new Courses();
        $course4->setName('Cursus d’initiation au jardinage');
        $course4->setPrice('30.00');
        $course4->setDescription('Cursus d’initiation au jardinage');
        $course4->setCreatedBy('admin');
        $course4->setUpdatedBy('admin');
        $course4->setCreatedAt(new DateTimeImmutable());
        $course4->setUpdatedAt(new DateTimeImmutable());
        $course4->setTheme($theme3);

        $course5 = new Courses();
        $course5->setName('Cursus d’initiation à la cuisine');
        $course5->setPrice('44.00');
        $course5->setDescription('Cursus d’initiation à la cuisine');
        $course5->setCreatedBy('admin');
        $course5->setUpdatedBy('admin');
        $course5->setCreatedAt(new DateTimeImmutable());
        $course5->setUpdatedAt(new DateTimeImmutable());
        $course5->setTheme($theme4);

        $course6 = new Courses();
        $course6->setName('Cursus d’initiation à l’art du dressage culinaire');
        $course6->setPrice('48.00');
        $course6->setDescription('Cursus d’initiation à l’art du dressage culinaire');
        $course6->setCreatedBy('admin');
        $course6->setUpdatedBy('admin');
        $course6->setCreatedAt(new DateTimeImmutable());
        $course6->setUpdatedAt(new DateTimeImmutable());
        $course6->setTheme($theme4);
        
        $manager->persist($course1);
        $manager->persist($course2);
        $manager->persist($course3);
        $manager->persist($course4);
        $manager->persist($course5);
        $manager->persist($course6);

        // Create Lessons

        $lesson1 = new Lessons();
        $lesson1->setName('Découverte de l’instrument');
        $lesson1->setDescription('Découverte de l’instrument');
        $lesson1->setPrice('26.00');
        $lessons1->setVideoUrl('');
        $lesson1->setCreatedBy('admin');
        $lesson1->setUpdatedBy('admin');
        $lesson1->setCreatedAt(new DateTimeImmutable());
        $$lesson1->setUpdatedAt(new DateTimeImmutable());
        $lesson1->setCourse($course1);
        
        $lesson2 = new Lessons();
        $lesson2->setName('Les accords et les gammes');
        $lesson2->setDescription('Les accords et les gammes');
        $lesson2->setPrice('26.00');
        $lessons2->setVideoUrl('');
        $lesson2->setCreatedBy('admin');
        $lesson2->setUpdatedBy('admin');
        $lesson2->setCreatedAt(new DateTimeImmutable());
        $lesson2->setUpdatedAt(new DateTimeImmutable());
        $lesson2->setCourse($course1);

        $lesson3 = new Lessons();
        $lesson3->setName('Découverte de l’instrument');
        $lesson3->setDescription('Découverte de l’instrument');
        $lesson3->setPrice('26.00');
        $lessons3->setVideoUrl('');
        $lesson3->setCreatedBy('admin');
        $lesson3->setUpdatedBy('admin');
        $lesson3->setCreatedAt(new DateTimeImmutable());
        $lesson3->setUpdatedAt(new DateTimeImmutable());
        $lesson3->setCourse($course2);

        $lesson4 = new Lessons();
        $lesson4->setName('Les accords et les gammes');
        $lesson4->setDescription('Les accords et les gammes');
        $lesson4->setPrice('26.00');
        $lessons4->setVideoUrl('');
        $lesson4->setCreatedBy('admin');
        $lesson4->setUpdatedBy('admin');
        $lesson4->setCreatedAt(new DateTimeImmutable());
        $lesson4->setUpdatedAt(new DateTimeImmutable());
        $lesson4->setCourse($course2);

        $lesson5 = new Lessons();
        $lesson5->setName('Les langages Html et CSS');
        $lesson5->setDescription('Les langages Html et CSS');
        $lesson5->setPrice('32.00');
        $lessons5->setVideoUrl('Videos/Video_lecon_langage_html_css.mp4');
        $lesson5->setCreatedBy('admin');
        $lesson5->setUpdatedBy('admin');
        $lesson5->setCreatedAt(new DateTimeImmutable());
        $lesson5->setUpdatedAt(new DateTimeImmutable());
        $lesson5->setCourse($course3);

        $lesson6 = new Lessons();
        $lesson6->setName('Dynamiser votre site avec Javascript');
        $lesson6->setDescription('Dynamiser votre site avec Javascript');
        $lesson6->setPrice('32.00');
        $lessons6->setVideoUrl('');
        $lesson6->setCreatedBy('admin');
        $lesson6->setUpdatedBy('admin');
        $lesson6->setCreatedAt(new DateTimeImmutable());
        $lesson6->setUpdatedAt(new DateTimeImmutable());
        $lesson6->setCourse($course3);

        $lesson7 = new Lessons();
        $lesson7->setName('Les outils du jardinier');
        $lesson7->setDescription('Les outils du jardinier');
        $lesson7->setPrice('16.00');
        $lessons7->setVideoUrl('');
        $lesson7->setCreatedBy('admin');
        $lesson7->setUpdatedBy('admin');
        $lesson7->setCreatedAt(new DateTimeImmutable());
        $lesson7->setUpdatedAt(new DateTimeImmutable());
        $lesson7->setCourse($course4);

        $lesson8 = new Lessons();
        $lesson8->setName('Jardiner avec la lune');
        $lesson8->setDescription('Jardiner avec la lune');
        $lesson8->setPrice('16.00');
        $lessons8->setVideoUrl('');
        $lesson8->setCreatedBy('admin');
        $lesson8->setUpdatedBy('admin');
        $lesson8->setCreatedAt(new DateTimeImmutable());
        $lesson8->setUpdatedAt(new DateTimeImmutable());
        $lesson8->setCourse($course4);

        $lesson9 = new Lessons();
        $lesson9->setName('Les modes de cuisson');
        $lesson9->setDescription('Les modes de cuisson');
        $lesson9->setPrice('23.00');
        $lessons9->setVideoUrl('');
        $lesson9->setCreatedBy('admin');
        $lesson9->setUpdatedBy('admin');
        $lesson9->setCreatedAt(new DateTimeImmutable());
        $lesson9->setUpdatedAt(new DateTimeImmutable());
        $lesson9->setCourse($course5);

        $lesson10 = new Lessons();
        $lesson10->setName('Les saveurs');
        $lesson10->setDescription('Les saveurs');
        $lesson10->setPrice('23.00');
        $lessons10->setVideoUrl('');
        $lesson10->setCreatedBy('admin');
        $lesson10->setUpdatedBy('admin');
        $lesson10->setCreatedAt(new DateTimeImmutable());
        $lesson10->setUpdatedAt(new DateTimeImmutable());
        $lesson10->setCourse($course5);

        $lesson11 = new Lessons();
        $lesson11->setName('Mettre en œuvre le style dans l’assiette');
        $lesson11->setDescription('Mettre en œuvre le style dans l’assiette');
        $lesson11->setPrice('26.00');
        $lessons11->setVideoUrl('Videos/Video_mettre_en_œuvre_le_style _dans_assiette.mp4');
        $lesson11->setCreatedBy('admin');
        $lesson11->setUpdatedBy('admin');
        $lesson11->setCreatedAt(new DateTimeImmutable());
        $lesson11->setUpdatedAt(new DateTimeImmutable());
        $lesson11->setCourse($course6);

        $lesson12 = new Lessons();
        $lesson12->setName('Harmoniser un repas à quatre plats');
        $lesson12->setDescription('Harmoniser un repas à quatre plats');
        $lesson12->setPrice('26.00');
        $lessons12->setVideoUrl('');
        $lesson12->setCreatedBy('admin');
        $lesson12->setUpdatedBy('admin');
        $lesson12->setCreatedAt(new DateTimeImmutable());
        $lesson12->setUpdatedAt(new DateTimeImmutable());
        $lesson12->setCourse($course6);

        $manager->persist($lesson1);
        $manager->persist($lesson2);
        $manager->persist($lesson3);
        $manager->persist($lesson4);
        $manager->persist($lesson5);
        $manager->persist($lesson6);
        $manager->persist($lesson7);
        $manager->persist($lesson8);
        $manager->persist($lesson9);
        $manager->persist($lesson10);
        $manager->persist($lesson11);
        $manager->persist($lesson12);

        // Persist all created objects
        $manager->flush();
    }
}
