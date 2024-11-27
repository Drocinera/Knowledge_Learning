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

class FormationsSubscriber implements EventSubscriberInterface
{
    private ThemesRepository $themesRepository;
    private CoursesRepository $coursesRepository;
    private LessonsRepository $lessonsRepository;
    private $twig;

    public function __construct(ThemesRepository $themesRepository,CoursesRepository $coursesRepository, LessonsRepository $lessonsRepository, Environment $twig)
    {
        $this->themesRepository = $themesRepository;
        $this->coursesRepository = $coursesRepository;
        $this->lessonsRepository = $lessonsRepository;
        $this->twig = $twig;
    }

    public function onKernelController(ControllerEvent $event)
    {
        $formations = $this->themesRepository->findAllFormations();
        $courses = $this->coursesRepository->findAllCourses();
        $lessons = $this->lessonsRepository->findAllLessons();
        $this->twig->addGlobal('formations', $formations);
        $this->twig->addGlobal('courses' , $courses);
        $this->twig->addGlobal('lessons' , $lessons);
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }
}
