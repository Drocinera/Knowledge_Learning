<?php


// src/EventSubscriber/FormationsSubscriber.php

namespace App\EventSubscriber;

use App\Repository\ThemesRepository;
use App\Repository\CoursesRepository;
use App\Repository\LessonsRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Twig\Environment;

/**
 * Event subscriber for Doctrine lifecycle events.
 * 
 * this subscriber allows access to the theme, course and lesson globally in all Twig files
 */

class FormationsSubscriber implements EventSubscriberInterface
{
    /**
     * @var ThemesRepository $themesRepository Repository for theme.
     * @var CoursesRepository $coursesRepository Repository for course.
     * @var LessonsRepository $lessonsRepository Repository for lessons.
     * @var Environment $twig Twig environnement.
     */

    private ThemesRepository $themesRepository;
    private CoursesRepository $coursesRepository;
    private LessonsRepository $lessonsRepository;
    private $twig;


    /**
     * Constructor.
     *
     * @param ThemesRepository $themesRepository Repository for theme.
     * @param CoursesRepository $coursesRepository Repository for course.
     * @param LessonsRepository $lessonsRepository Repository for lessons.
     * @param Environment $twig Twig environnement.
     * 
     */

    public function __construct(ThemesRepository $themesRepository,CoursesRepository $coursesRepository, LessonsRepository $lessonsRepository, Environment $twig)
    {
        $this->themesRepository = $themesRepository;
        $this->coursesRepository = $coursesRepository;
        $this->lessonsRepository = $lessonsRepository;
        $this->twig = $twig;
    }

    
    /**
     * allows the theme, course and lesson to be accessible everywhere in Twig
     * 
     * @param ControllerEvent $event Symfony component for event.
     *
     * @return Response  Create 3 variables for twig. 
     * 
     */

    public function onKernelController(ControllerEvent $event)
    {
        $formations = $this->themesRepository->findAllFormations();
        $courses = $this->coursesRepository->findAllCourses();
        $lessons = $this->lessonsRepository->findAllLessons();
        $this->twig->addGlobal('formations', $formations);
        $this->twig->addGlobal('courses' , $courses);
        $this->twig->addGlobal('lessons' , $lessons);
    }

    /**
     * Function which allows the use of previously defined variables without having to declare them explicitly
     * 
     * @return KernelEvents  Method called to implement defined variables
     * 
     */
    
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }
}
