<?php

namespace W3build\MediaBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Gallery
 *
 * @ORM\Table(name="media_gallery")
 * @ORM\Entity(repositoryClass="W3build\MediaBundle\Entity\GalleryRepository")
 */
class Gallery
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="Medium", mappedBy="gallery")
     * @ORM\OrderBy({"order" = "ASC"})
     */
    private $mediums;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->mediums = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Gallery
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Gallery
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Add mediums
     *
     * @param \W3build\MediaBundle\Entity\Medium $mediums
     * @return Gallery
     */
    public function addMedium(\W3build\MediaBundle\Entity\Medium $mediums)
    {
        $this->mediums[] = $mediums;

        return $this;
    }

    /**
     * Remove mediums
     *
     * @param \W3build\MediaBundle\Entity\Medium $mediums
     */
    public function removeMedium(\W3build\MediaBundle\Entity\Medium $mediums)
    {
        $this->mediums->removeElement($mediums);
    }

    /**
     * Get mediums
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMediums()
    {
        return $this->mediums;
    }
}
