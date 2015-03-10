<?php

namespace W3build\MediaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use W3build\MediaBundle\Service\Image\ImageInterface;

/**
 * Image
 *
 * @ORM\Table(name="media_image")
 * @ORM\Entity(repositoryClass="W3build\MediaBundle\Repository\ImageRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Image extends Medium implements ImageInterface
{
    const UPLOAD_PATH = 'web/uploads';

    const ENTITY_NAME = 'W3buildMediaBundle:Image';

    /**
     * @Assert\File(maxSize="4M")
     *
     * @var UploadedFile|string
     */
    private $file;

    /**
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=255)
     */
    private $path = self::UPLOAD_PATH;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated", type="datetime")
     */
    private $updated;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

    /**
     * @var string
     *
     * @ORM\Column(name="file_name", type="string", length=255)
     */
    private $fileName;

    /**
     * @var string
     *
     * @ORM\Column(name="extension", type="string", length=5)
     */
    private $extension;

    /**
     * @var int
     *
     * @ORM\Column(name="image_type", type="integer")
     */
    private $imageType;

    /**
     * @var int
     *
     * @ORM\Column(name="width", type="integer")
     */
    private $width;

    /**
     * @var int
     *
     * @ORM\Column(name="height", type="integer")
     */
    private $height;

    /**
     * @var int
     *
     * @ORM\Column(name="ratio", type="float")
     */
    private $ratio;

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function prePersists(){
        if (null === $this->getFile()) {
            return;
        }

        if(!$this->getId()){
            $this->created = new \DateTime();
        }

        $fileName = sha1(uniqid(mt_rand(), true));

        if (!$this->getFile() instanceof UploadedFile){
            if(preg_match('#^https?#', $this->getFile())){
                $tmpFile = realpath('/tmp') . DIRECTORY_SEPARATOR . uniqid('_tmp' . mt_rand(), true);
                file_put_contents($tmpFile, file_get_contents($this->getFile()));
                $this->setFile($tmpFile);
            }

            $this->setFile(new UploadedFile(realpath($this->getFile()), pathinfo($this->getFile(), PATHINFO_BASENAME), null, null, UPLOAD_ERR_OK, true));
        }

        $this->extension = $this->getFile()->guessExtension();
        $this->getFile()->move($this->getUploadDirAbsolutePath(), $fileName . '.' . $this->getExtension());

        if ($this->getFileName()) {
            unlink($this->getAbsolutePath());
        }

        $this->fileName = $fileName;
        list($width, $height, $imageType) = getimagesize($this->getAbsolutePath());
        $this->width = $width;
        $this->height = $height;
        $this->imageType = $imageType;
        $this->ratio = $width / $height;
    }

    public function getType()
    {
        return parent::TYPE_IMAGE;
    }

    /**
     * @return UploadedFile|string
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param mixed $file
     */
    public function setFile($file)
    {
        $this->updated = new \DateTime();
        $this->file = $file;
    }

    public function getBaseName(){
        return $this->getFileName() . '.' . $this->getExtension();
    }

    public function getUploadDirAbsolutePath(){
        $root = str_replace('vendor/w3build/media-bundle/src/W3build/MediaBundle/Entity', '', __DIR__);
        return realpath($root . $this->path);
    }

    public function getAbsolutePath(){
        return $this->getUploadDirAbsolutePath() . DIRECTORY_SEPARATOR . $this->getBaseName();
    }

    public function getWebPath(){
        return DIRECTORY_SEPARATOR . str_replace('web/', '', $this->getPath()) . DIRECTORY_SEPARATOR . $this->getBaseName();
    }


    /**
     * Set path
     *
     * @param string $path
     * @return Image
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     * @return Image
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime 
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Image
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime 
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set fileName
     *
     * @param string $fileName
     * @return Image
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;

        return $this;
    }

    /**
     * Get fileName
     *
     * @return string 
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * Set extension
     *
     * @param string $extension
     * @return Image
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;

        return $this;
    }

    /**
     * Get extension
     *
     * @return string 
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * Set imageType
     *
     * @param integer $imageType
     * @return Image
     */
    public function setImageType($imageType)
    {
        $this->imageType = $imageType;

        return $this;
    }

    /**
     * Get imageType
     *
     * @return integer 
     */
    public function getImageType()
    {
        return $this->imageType;
    }

    /**
     * Set width
     *
     * @param integer $width
     * @return Image
     */
    public function setWidth($width)
    {
        $this->width = $width;

        return $this;
    }

    /**
     * Get width
     *
     * @return integer 
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Set height
     *
     * @param integer $height
     * @return Image
     */
    public function setHeight($height)
    {
        $this->height = $height;

        return $this;
    }

    /**
     * Get height
     *
     * @return integer 
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Set ratio
     *
     * @param float $ratio
     * @return Image
     */
    public function setRatio($ratio)
    {
        $this->ratio = $ratio;

        return $this;
    }

    /**
     * Get ratio
     *
     * @return float 
     */
    public function getRatio()
    {
        return $this->ratio;
    }
}
