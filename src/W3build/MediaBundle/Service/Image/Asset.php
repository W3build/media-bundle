<?php
/**
 * Created by PhpStorm.
 * User: lukas_jahoda
 * Date: 5.1.15
 * Time: 12:13
 */

namespace W3build\MediaBundle\Service\Image;


use Assetic\Factory\AssetFactory;

class Asset implements ImageInterface {

    /**
     * @var AssetFactory
     */
    private $assetFactory;

    private $absolutePath;

    private $width;

    private $height;

    private $imageType;

    private $ratio;

    private $fileName;

    private $extension;

    /**
     * @param AssetFactory $assetFactory
     */
    public function __construct(AssetFactory $assetFactory){
        $this->assetFactory = $assetFactory;

        return $this;
    }

    public function setAsset($asset){
        $assets = $this->assetFactory->createAsset($asset)->all();
        $asset = $assets[0];
        $absolutePath = $asset->getSourceRoot() . DIRECTORY_SEPARATOR . $asset->getSourcePath();
        $pathInfo = pathinfo($absolutePath);
        list($width, $height, $imageType) = getimagesize($absolutePath);
        $this->absolutePath = $absolutePath;
        $this->width = $width;
        $this->height = $height;
        $this->imageType = $imageType;
        $this->ratio = $width / $height;
        $this->fileName = $pathInfo['filename'];
        $this->extension = $pathInfo['extension'];

        return $this;
    }

    public function getRatio()
    {
        return $this->ratio;
    }

    public function getImageType()
    {
        return $this->imageType;
    }

    public function getWidth()
    {
        return $this->width;
    }

    public function getHeight()
    {
        return $this->height;
    }

    public function getAbsolutePath()
    {
        return $this->absolutePath;
    }

    public function getUploadDirAbsolutePath()
    {
        $root = str_replace('vendor/w3build/media-bundle/src/W3build/MediaBundle/Service/Image', '', __DIR__);
        return realpath($root . 'web/uploads');
    }

    public function getFileName()
    {
        return $this->fileName;
    }

    public function getExtension()
    {
        return $this->extension;
    }


}