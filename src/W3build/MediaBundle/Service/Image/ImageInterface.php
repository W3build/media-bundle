<?php
/**
 * Created by PhpStorm.
 * User: lukas_jahoda
 * Date: 5.1.15
 * Time: 12:14
 */

namespace W3build\MediaBundle\Service\Image;


interface ImageInterface {

    public function getRatio();

    public function getImageType();

    public function getWidth();

    public function getHeight();

    public function getAbsolutePath();

    public function getUploadDirAbsolutePath();

    public function getFileName();

    public function getExtension();
}