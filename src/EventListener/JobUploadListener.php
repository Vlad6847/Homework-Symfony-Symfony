<?php

namespace App\EventListener;

use App\Service\FileUploader;
use App\Entity\Job;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class JobUploadListener
{
    /**
     * @var FileUploader
     */
    private $uploader;

    /**
     * @param FileUploader $uploader
     */
    public function __construct(FileUploader $uploader)
    {
        $this->uploader = $uploader;
    }

    /**
     * @param LifecycleEventArgs $args
     *
     * @throws \Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException
     */
    public function postLoad(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();

        $this->stringToFile($entity);
    }

    /**
     * @param $entity
     *
     * @throws \Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException
     */
    private function stringToFile($entity): void
    {
        if (!$entity instanceof Job){
            return;
        }

        if ($fileName = $entity->getLogo()) {
            $entity->setLogo(new File($this->uploader->getTargetDirectory() . '/' . $fileName));
        }
    }

    /**
     * @param LifecycleEventArgs $args
     *
     * @throws \Symfony\Component\HttpFoundation\File\Exception\FileException
     */
    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();

        $this->uploadFile($entity);
    }

    /**
     * @param LifecycleEventArgs $args
     *
     * @throws \Symfony\Component\HttpFoundation\File\Exception\FileException
     */
    public function preUpdate(LifecycleEventArgs $args):void
    {
        $entity = $args->getEntity();

        $this->uploadFile($entity);
    }

    /**
     * @param $entity
     *
     * @throws \Symfony\Component\HttpFoundation\File\Exception\FileException
     */
    private function uploadFile($entity): void
    {
        if (!$entity instanceof Job) {
            return;
        }
        $logoFile = $entity->getLogo();

        if ($logoFile instanceof UploadedFile) {
            $fileName = $this->uploader->upload($logoFile);

            $entity->setLogo($fileName);
        }
    }
}
