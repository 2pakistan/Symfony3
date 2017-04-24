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

            $fileToModify = null ;
            //get dates of update of profile pic and cover pic
            $dateUpdateProfile = $entity->getUpdatedAtProfile();
            $dateUpdateCover = $entity->getUpdatedAt();

            //compare what is the last updated element between the two
            if($dateUpdateProfile < $dateUpdateCover){
                $fileToModify = 'cover';
                $file = $entity->getImagefilecover();
            }else{
                $fileToModify = 'profile';
                $file = $entity->getImagefile();
            }
            $path = $file->getRealPath();

            if($fileToModify === 'profile'){
                $this->imgService->open($path)
                    ->zoomCrop(200, 200)
                    ->save($path);
            }else{
                $this->imgService->open($path)
                    ->zoomCrop(1700, 800, '#ffffff', 'center', 'center')
                    ->save($path);
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