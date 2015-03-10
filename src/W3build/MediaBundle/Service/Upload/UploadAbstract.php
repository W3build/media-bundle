<?php
/**
 * Created by PhpStorm.
 * User: Jahodal
 * Date: 7.8.14
 * Time: 21:14
 */

namespace W3build\MediaBundle\Service\Upload;


use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use W3build\MediaBundle\Entity\Medium;

abstract class UploadAbstract {

    const UPLOAD_FILE_DIR = 'uploads';

    private $path;

    private $originalFileName;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var Medium
     */
    private $medium;

    private $old_file;

    private $upload = false;

    abstract public function upload($path, Medium $medium = null);

    public function setMedium(Medium $medium){
        $this->medium = $medium;

        return $this;
    }

    public function getMedium(){
        return $this->medium;
    }

    public function setPath($path){
        $this->path = $path;

        return $this;
    }

    private function getPath(){
        return $this->path;
    }

    public function setOriginalFileName($originalFileName){
        $this->originalFileName = $originalFileName;

        return $this;
    }

    public function getOriginalFileName(){
        return $this->originalFileName;
    }

    public function executeUpload(){
        $uploadedFile = new UploadedFile($this->getPath(), $this->getOriginalFileName(), null, null, null, true);
        if(!$this->medium){
            $this->medium = new Medium();
        }

        $this->uploadFile($uploadedFile);

        $this->entityManager->persist($this->medium);
        $this->entityManager->flush();
        $medium = $this->medium;
        $this->medium = null;
        return $medium;
    }

    public function __construct(EntityManager $entityManager){
        $this->entityManager = $entityManager;
    }

    private function getUploadDir(){
        return self::UPLOAD_FILE_DIR;
    }

    private function getUploadRootDir(){
        return realpath(__DIR__ . '/../../../../../web/' .  $this->getUploadDir());
    }

    public function getFilePath(){

        return $this->medium->getPath() . '/' . $this->medium->getFile();
    }

    public function getAbsolutePath(){
        return realpath(__DIR__ . '/../../../../../web/' . $this->medium->getPath() . $this->getFilePath());
    }

    /**
     * @param UploadedFile $uploadedFile
     */
    public function uploadFile(UploadedFile $uploadedFile)
    {
        if (null === $uploadedFile) {
            return ;
        }

        $file = sha1(uniqid(mt_rand(), true)) . '.' . $uploadedFile->guessExtension();
        $uploadedFile->move($this->getUploadRootDir(), $file);

        if ($this->medium->getFile()) {
            $this->removeUpload();
        }

        $this->medium->setFile($file);
        $this->medium->setPath($this->getUploadDir());
        $this->medium->setCreated(new \DateTime());
        $this->medium->setUpdated(new \DateTime());
    }

    public function removeUpload()
    {
        if ($file = $this->getAbsolutePath()) {
            unlink($file);
        }
    }

} 