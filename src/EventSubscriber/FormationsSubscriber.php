<?php


// src/EventSubscriber/FormationsSubscriber.php

namespace App\EventSubscriber;

use App\Repository\ThemesRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Twig\Environment;

class FormationsSubscriber implements EventSubscriberInterface
{
    private $ThemesRepository;
    private $twig;

    public function __construct(ThemesRepository $themesRepository, Environment $twig)
    {
        $this->themesRepository = $themesRepository;
        $this->twig = $twig;
    }

    public function onKernelController(ControllerEvent $event)
    {
        $formations = $this->themesRepository->findAllFormations();
        $this->twig->addGlobal('formations', $formations);
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }
}
