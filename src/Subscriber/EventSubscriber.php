<?php


/// src/EventSubscriber/EntitySubscriber.php
namespace App\EventSubscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class EntitySubscriber implements EventSubscriber
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::prePersist,
            Events::preUpdate,
        ];
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();
        $user = $this->security->getUser();

        if ($user instanceof UserInterface) {
            // Vérifier si l'entité possède les méthodes setCreatedBy et setUpdatedBy
            if (method_exists($entity, 'setCreatedBy')) {
                $entity->setCreatedBy($user->getId()); // Utilisez $user->getEmail() si nécessaire
            }
            if (method_exists($entity, 'setUpdatedBy')) {
                $entity->setUpdatedBy($user->getId());
            }
        }
    }

    public function preUpdate(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();
        $user = $this->security->getUser();

        if ($user instanceof UserInterface) {
            // Vérifier si l'entité possède la méthode setUpdatedBy
            if (method_exists($entity, 'setUpdatedBy')) {
                $entity->setUpdatedBy($user->getId());
            }
        }
    }
}
