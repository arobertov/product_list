<?php
/**
 * Created by PhpStorm.
 * User: Angel
 * Date: 15.6.2017 г.
 * Time: 2:04
 */

namespace SoftuniProductsBundle\Services;


class FileManager
{
    private $targetDir,$class;

    public function __construct($targetDir,$class)
    {
       $this->targetDir =$targetDir;
       $this->class = $class;
    }

    public function editImage($targetDir,$class)
    {

    }

    /**
     * @return mixed
     */
    public function getTargetDir()
    {
        return $this->targetDir;
    }

    /**
     * @param mixed $targetDir
     */
    public function setTargetDir($targetDir)
    {
        $this->targetDir = $targetDir;
    }

}