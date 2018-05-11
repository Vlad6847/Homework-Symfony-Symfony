<?php

namespace App\EventListener;

use App\Entity\Job;
use Doctrine\ORM\Event\LifecycleEventArgs;

class JobTokenListener
{
    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();

        $this->generateToken($entity);
    }

    private function generateToken($entity): void
    {
        if (!$entity instanceof Job) {
            return;
        }

        $token = \bin2hex(\random_bytes(10));
        $entity->setToken($token);
    }
}
