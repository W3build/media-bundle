<?php
/**
 * Created by PhpStorm.
 * User: Jahodal
 * Date: 7.8.14
 * Time: 21:13
 */

namespace W3build\MediaBundle\Service;


use W3build\MediaBundle\Entity\Medium;
use W3build\MediaBundle\Service\Upload\UploadAbstract;

class UploadFromUrl extends UploadAbstract {


    public function upload($path, Medium $medium = null)
    {
        $tmpFile = uniqid('_tmp' . mt_rand(), true);
        file_put_contents($tmpFile, file_get_contents($path));
        $this->setOriginalFileName(basename($path));
        $this->setPath($tmpFile);
        if($medium){
            $this->setMedium($medium);
        }

        $medium = $this->executeUpload();
        return $medium;
    }
}