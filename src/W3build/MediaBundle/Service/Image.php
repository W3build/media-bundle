<?php
/**
 * Created by PhpStorm.
 * User: lukas_jahoda
 * Date: 12.12.14
 * Time: 2:53
 */

namespace W3build\MediaBundle\Service;

use \W3build\MediaBundle\Entity as Entity;
use W3build\MediaBundle\Service\Image\ImageInterface;


class Image {

    /**
     * @var Entity\Image $image
     */
    private $image;

    /**
     * @var resource
     */
    private $gdImageResource;

    /**
     * @return Entity\Image
     * @throws \Exception
     */
    private function getImage(){
        if(!$this->image){
            throw new \Exception('No image entity was set', 500);
        }

        return $this->image;
    }

    private function getResizedAbsolutePath($width = null, $height = null, $crop = false, $cropX = 'center', $cropY = 'center'){
        $specification = '_' . $width . '_' . $height;

        if($crop){
            $specification .= '_' . $cropX . '_' . $cropY;
        }

        return $this->getImage()->getUploadDirAbsolutePath() . DIRECTORY_SEPARATOR . 'thumbs' . DIRECTORY_SEPARATOR . $this->getImage()->getFileName() . $specification . '.' . $this->getImage()->getExtension();
    }

    private function getResized($width = null, $height = null, $crop = false, $cropX = 'center', $cropY = 'center'){
        $resizedAbsolutePath = $this->getResizedAbsolutePath($width, $height, $crop, $cropX, $cropY);

        if(!file_exists($resizedAbsolutePath)){
            return null;
        }

        return $resizedAbsolutePath;
    }

    /**
     * @return resource
     * @throws \Exception
     */
    private function getGdImageResource(){
        if(!$this->gdImageResource){
            switch($this->getImage()->getImageType()){
                case IMAGETYPE_JPEG:
                    $this->gdImageResource = imagecreatefromjpeg($this->getImage()->getAbsolutePath());
                    break;
                case IMAGETYPE_PNG:
                    $this->gdImageResource = imagecreatefrompng($this->getImage()->getAbsolutePath());
                    break;
                case IMAGETYPE_GIF:
                    $this->gdImageResource = imagecreatefromgif($this->getImage()->getAbsolutePath());
                    break;
                default:
                    throw new \Exception('Unsupported image type type ' . image_type_to_mime_type($this->getImageType()));
            }
        }

        return $this->gdImageResource;
    }

    private function createImage($tmp, $newWidth, $newHeight, $file, $cropX = 0, $cropY = 0){
        if ($this->getImage()->getImageType() == IMAGETYPE_GIF or $this->getImage()->getImageType() == IMAGETYPE_PNG) {
            imagecolortransparent($tmp, imagecolorallocatealpha($tmp, 0, 0, 0, 127));
            imagealphablending($tmp, false);
            imagesavealpha($tmp, true);
        }

        imagecopyresampled(
            $tmp,
            $this->getGdImageResource(),
            $cropX,
            $cropY,
            0,
            0,
            $newWidth,
            $newHeight,
            $this->getImage()->getWidth(),
            $this->getImage()->getHeight()
        );

        switch($this->getImage()->getImageType()){
            case IMAGETYPE_JPEG:
                imagejpeg($tmp, $file, 80);
                break;
            case IMAGETYPE_PNG:
                imagepng($tmp, $file);
                break;
            case IMAGETYPE_GIF:
                imagegif($tmp, $file);
                break;
            default:
                throw new \Exception('Unsupported image type type ' . image_type_to_mime_type($this->_getImageType()));
        }

        return $file;
    }

    private function crop($width, $height, $fileName, $cropX = 'center', $cropY = 'center'){
        $newWidth = $width;
        $newHeight = $height;

        $ratio = $width / $height;
        if($ratio != $this->getImage()->getRatio()){
            if($ratio < $this->getImage()->getRatio()){
                $newHeight =  $height;
                $newWidth = $height * $this->getImage()->getRatio();
            }
            else {
                $newWidth =  $width;
                $newHeight = $width / $this->getImage()->getRatio();
            }
        }

        switch ($cropX){
            case 'center':
                $cropX = (($newWidth - $width) / 2) * -1;
                break;
            case 'left':
                $cropX = 0;
                break;
            case 'right':
                $cropX = ($newWidth - $width) * -1;
                break;
            default:
                throw new \Exception('Incorect cropY: "' . $cropX . '" given');
        }

        switch ($cropY){
            case 'center':
                $cropY = (($newHeight - $height) / 2) * -1;
                break;
            case 'top':
                $cropY = 0;
                break;
            case 'bottom':
                $cropY = ($newHeight - $height) * -1;
                break;
            default:
                throw new \Exception('Incorect cropY: "' . $cropY . '" given');
        }

        $tmp = imagecreatetruecolor($width, $height);

        return $this->createImage($tmp, $newWidth, $newHeight, $fileName, $cropX, $cropY);
    }

    /**
     * @param ImageInterface $image
     * @return $this
     */
    public function setImage(ImageInterface $image){
        $this->image = $image;

        return $this;
    }

    public function resize($maxWidth = null, $maxHeight = null, $crop = false, $cropX = 'center', $cropY = 'center', $pixelRatio = 1)
    {
        if (!$maxWidth && !$maxHeight) {
            return;
        }

        $maxWidth = $maxWidth * ceil($pixelRatio);
        $maxHeight = $maxHeight * ceil($pixelRatio);

        if ($resized = $this->getResized($maxWidth, $maxHeight, $crop, $cropX, $cropY)) {
            return $resized;
        }

        $fileName = $this->getResizedAbsolutePath($maxWidth, $maxHeight, $crop, $cropX, $cropY);

        if($crop){
            if(!$maxHeight || !$maxWidth){
                throw new \Exception('No dimensions was sett');
            }
            return $this->crop($maxWidth, $maxHeight, $fileName, $cropX, $cropY);
        }

        if (!$maxWidth) {
            $newHeight = $maxHeight;
            $newWidth = $maxHeight * $this->getImage()->getRatio();
        } elseif (!$maxHeight) {
            $newWidth = $maxWidth;
            $newHeight = $maxWidth / $this->getImage()->getRatio();
        }

        if($maxHeight && $maxWidth){
            if ($this->getImage()->getRatio() >= 1) {
                $newWidth = $maxWidth;
                $newHeight = $maxWidth / $this->getImage()->getRatio();
                if ($newHeight > $maxHeight) {
                    $newHeight = $maxHeight;
                    $newWidth = $maxHeight * $this->getImage()->getRatio();
                }
            } else {
                $newHeight = $maxHeight;
                $newWidth = $maxHeight * $this->getImage()->getRatio();
                if ($newWidth > $maxWidth) {
                    $newWidth = $maxWidth;
                    $newHeight = $maxWidth / $this->getImage()->getRatio();
                }
            }
        }

        $tmp = imagecreatetruecolor($newWidth, $newHeight);

        return $this->createImage($tmp, $newWidth, $newHeight, $fileName);
    }

}