<?php

namespace W3build\MediaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use W3build\MediaBundle\Form\Constraints as Assert;
use Doctrine\ORM\Event\LifecycleEventArgs;

/**
 * YouTube
 *
 * @ORM\Table(name="media_youtube")
 * @ORM\Entity(repositoryClass="W3build\MediaBundle\Repository\YouTubeRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class YouTube extends Medium implements MediumInterface
{

    /**
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=255)
     * @Assert\YouTube()
     */
    private $path;

    /**
     * @var sting
     *
     * @ORM\Column(name="youtube_id", type="string", length=50)
     */
    private $youtubeId;

    private function setYoutubeIdFromPath(){
        preg_match('#https?://www\.youtube\.com/watch\?v=([^&]*)#', $this->path, $matches);
        $this->setYoutubeId($matches[1]);
    }

    /**
     * @ORM\PrePersist()
     */
    public function prePersists(){
        $this->setYoutubeIdFromPath();
    }

    /**
     * @param LifecycleEventArgs $lifecycleEventArgs
     *
     * @ORM\PreUpdate()
     */
    public function preUpdate(LifecycleEventArgs $lifecycleEventArgs){
        $entity = $lifecycleEventArgs->getEntity();

        $this->entityManager = $lifecycleEventArgs->getEntityManager();
        if(!$entity instanceof UrlInterface){
            return;
        }

        $changeSet = $this->entityManager->getUnitOfWork()->getEntityChangeSet($entity);

        if(isset($changeSet['path'])){
            $this->setYoutubeIdFromPath();
        }
    }

    public function getType()
    {
        return parent::TYPE_YOUTUBE;
    }


    /**
     * Set path
     *
     * @param string $path
     * @return YouTube
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
     * @return sting
     */
    public function getYoutubeId()
    {
        return $this->youtubeId;
    }

    /**
     * @param sting $youtubeId
     * @return $this
     */
    public function setYoutubeId($youtubeId)
    {
        $this->youtubeId = $youtubeId;
        return $this;
    }
}
