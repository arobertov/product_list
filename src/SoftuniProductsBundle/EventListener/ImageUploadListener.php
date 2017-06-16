<?php
/**
 * Created by PhpStorm.
 * User: Angel
 * Date: 15.6.2017 г.
 * Time: 0:19
 */

namespace SoftuniProductsBundle\EventListener;


use SoftuniProductsBundle\Entity\Product;
use SoftuniProductsBundle\Services\FileUploader;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageUploadListener
{
    private $uploader;

    public function __construct(FileUploader $uploader)
    {
        $this->uploader = $uploader;
    }


    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        $this->uploadFile($entity);
    }

    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getEntity();
        if (!$entity instanceof Product) {
            return;
        }
        if($args->getNewValue('path')===null) {
          $args->setNewValue('path',$args->getOldValue('path'));
        }
        else {
            if($args->getOldValue('path')!==null)
            $this->uploader->removeFile($args->getOldValue('path'));

            $this->uploadFile($entity);
        }
    }

    private function uploadFile($entity)
    {
        // upload only works for Product entities
        if (!$entity instanceof Product) {
            return;
        }

        $file = $entity->getPath();

        // only upload new files
        if (!$file instanceof UploadedFile) {
            return;
        }
        $fileName = $this->uploader->upload($file);
        $entity->setPath($fileName);
    }

    

}