<?php
/**
 * Created by PhpStorm.
 * User: Angel
 * Date: 15.6.2017 г.
 * Time: 14:36
 */

namespace SoftuniProductsBundle\EventListener;


use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use SoftuniProductsBundle\Entity\ProductCategory;
use SoftuniProductsBundle\Services\FileUploader;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class CategoryUploadListener
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
        if($args->getNewValue('path')===null) {
            $args->setNewValue('path',$args->getOldValue('path'));
        }
        else {
            if($args->getOldValue('path')!==null)
                $this->uploader->removeFile($args->getOldValue('path'));
            $entity = $args->getEntity();
            $this->uploadFile($entity);
        }
    }

    private function uploadFile($entity)
    {
        // upload only works for Product entities
        if (!$entity instanceof ProductCategory) {
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

    public function postLoad(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if (!$entity instanceof ProductCategory) {
            return;
        }

        if ($fileName = $entity->getPath()) {
            $entity->setPath(new File($this->uploader->getTargetDir().'/'.$fileName));
        }
    }
}