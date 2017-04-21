<?php
namespace VoyageBundle\EventListener;

use VoyageBundle\Entity\Utilisateurs;
use VoyageBundle\Entity\Medias;
use VoyageBundle\Entity\Voyages;

use Vich\UploaderBundle\Event\Event;

class UploadedFileListener
{
    private $imgService;


    public function setImgService($service)
    {
        $this->imgService = $service;
    }

    public function onPostUpload(Event $event)
    {
        $entity = $event->getObject();
        if ($entity instanceof Utilisateurs) {
            //dateInterval between update cover pic and profile pic
            $timeBetweenUpdate = date_diff($entity->getUpdatedAt(), $entity->getUpdatedAtProfile());

            //if this interval is under a min, we know that user updated both picture and cover pic
            if ($timeBetweenUpdate->i < 1) {
                $profilePic = $entity->getImagefile();
                $coverPic = $entity->getImagefilecover();
                $pathProfilePic = $profilePic->getRealPath();
                $pathCoverPic = $coverPic->getRealPath();
                $this->imgService->open($pathProfilePic)
                    ->zoomCrop(200, 200)
                    ->save($pathProfilePic);
                $this->imgService->open($pathCoverPic)
                    ->zoomCrop(1700, 800, '#ffffff', 'center', 'center')
                    ->save($pathCoverPic);
            } else {
                //we get the last updated element between profile pic or cover pic
                if ($entity->getUpdatedAt() > $entity->getUpdatedAtProfile()) {
                    $uploadedFile = $entity->getImagefilecover();
                    $filePath = $uploadedFile->getRealPath();

                    $this->imgService->open($filePath)
                        ->zoomCrop(1700, 800, '#ffffff', 'center', 'center')
                        ->save($filePath);

                } else {
                    $uploadedFile = $entity->getImagefile();
                    $filePath = $uploadedFile->getRealPath();

                    $this->imgService->open($filePath)
                        ->zoomCrop(200, 200, '#ffffff', 'center', 'center')
                        ->save($filePath);
                }
            }

        } elseif ($entity instanceof Voyages) {
            $uploadedFile = $entity->getImageFile();
            $filePath = $uploadedFile->getRealPath();

            $this->imgService->open($filePath)
                ->zoomCrop(1200, 500, '#ffffff', 'center', 'center')
                ->save($filePath);

        } elseif ($entity instanceof Medias) {
            $uploadedFile = $entity->getImageFile();
            $filePath = $uploadedFile->getRealPath();
            $this->imgService->open($filePath)
                ->zoomCrop(750, 500, '#ffffff', 'center', 'center')
                ->save($filePath);
        }

    }
}