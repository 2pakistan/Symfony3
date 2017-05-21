<?php

//Namespace where the class lives
namespace VoyageBundle\EventListener;

use VoyageBundle\Entity\Utilisateurs;
use VoyageBundle\Entity\Medias;
use VoyageBundle\Entity\Voyages;
use Vich\UploaderBundle\Event\Event;

//class Name, must follow the convention like : someNameListener
class UploadedFileListener
{
    private $imgService;

    public function __construct($imgService)
    {
        $this->imgService = $imgService;
    }

    //Method that is used when the event is triggered and listened
    public function onPostUpload(Event $event)
    {
        //Get the entity that has been modified.
        $entity = $event->getObject();

        //If the entity is an Utilisateurs, image uploaded is either profile or cover pic
        if ($entity instanceof Utilisateurs) {
            $fileToModify = null;
            //get update date of profile and cover pic
            $dateUpdateProfile = $entity->getUpdatedAtProfile();
            $dateUpdateCover = $entity->getUpdatedAt();

            //compare what is the last updated element between the two
            if ($dateUpdateProfile < $dateUpdateCover) {
                $fileToModify = 'cover';
                $file = $entity->getImagefilecover();
            } else {
                $fileToModify = 'profile';
                $file = $entity->getImagefile();
            }
            $path = $file->getRealPath();

            //If the file uploaded is a profile picture, ZoomCrop it to thumbnail format
            if ($fileToModify === 'profile') {
                $this->imgService->open($path)
                    ->zoomCrop(200, 200)
                    ->save($path);
                //if it's a cover picture, zoomCrop the pic to fit the banner format
            } else {
                $this->imgService->open($path)
                    ->zoomCrop(1700, 800, '#ffffff', 'center', 'center')
                    ->save($path);
            }
        //if entity is a Voyages, the uploaded file is the trip cover picture. ZoomCrop to large format
        } elseif ($entity instanceof Voyages) {
            $uploadedFile = $entity->getImageFile();
            $filePath = $uploadedFile->getRealPath();

            $this->imgService->open($filePath)
                ->zoomCrop(1200, 500, '#ffffff', 'center', 'center')
                ->save($filePath);
            //if entity is a Medias, the uploaded file is a trip step picture. ZoomCrop to medium format
        } elseif ($entity instanceof Medias) {
            $uploadedFile = $entity->getImageFile();
            $filePath = $uploadedFile->getRealPath();
            $this->imgService->open($filePath)
                ->zoomCrop(750, 500, '#ffffff', 'center', 'center')
                ->save($filePath);
        }

    }
}