<?php

namespace App\EventSubscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Event subscriber for Doctrine lifecycle events.
 * 
 * This subscriber listens to `prePersist` and `preUpdate` events and sets 
 * the createdBy and updatedBy properties for entities when applicable.
 */

class EntitySubscriber implements EventSubscriber
{
    /**
     * @var Security Security service to retrieve the current user.
     */

    private Security $security;

    /**
     * Constructor.
     *
     * @param Security $security The security service.
     */

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * Returns the list of events this subscriber listens to.
     *
     * @return array<string> The list of event names.
     */

    public function getSubscribedEvents(): array
    {
        return [
            Events::prePersist,
            Events::preUpdate,
        ];
    }

    /**
     * Handles the `prePersist` event.
     *
     * Sets the `createdBy` and `updatedBy` properties of the entity if applicable.
     *
     * @param LifecycleEventArgs $args The event arguments.
     */

    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();
        $user = $this->security->getUser();

        if ($user instanceof UserInterface) {
            // Set the createdBy property if the method exists
            if (method_exists($entity, 'setCreatedBy')) {
                $entity->setCreatedBy($user->getId()); // Use $user->getEmail() if needed
            }

            // Set the updatedBy property if the method exists
            if (method_exists($entity, 'setUpdatedBy')) {
                $entity->setUpdatedBy($user->getId());
            }
        }
    }

    /**
     * Handles the `preUpdate` event.
     *
     * Sets the `updatedBy` property of the entity if applicable.
     *
     * @param LifecycleEventArgs $args The event arguments.
     */

    public function preUpdate(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();
        $user = $this->security->getUser();

        if ($user instanceof UserInterface) {
            // Set the updatedBy property if the method exists
            if (method_exists($entity, 'setUpdatedBy')) {
                $entity->setUpdatedBy($user->getId());
            }
        }
    }
}
