<?php

namespace W3build\MediaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Medium
 *
 * @ORM\Table(name="medium")
 * @ORM\Entity(repositoryClass="W3build\MediaBundle\Entity\MediumRepository")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({Medium::TYPE_IMAGE="Image", Medium::TYPE_YOUTUBE="YouTube"})
 */
class Medium
{

    const TYPE_IMAGE = 'image';
    const TYPE_YOUTUBE = 'youTube';

    const ENTITY_NAME = 'W3buildMediaBundle:Medium';

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="W3build\MediaBundle\Entity\Gallery", inversedBy="mediums")
     */
    private $gallery;

    /**
     * @ORM\Column(name="`order`", type="integer", nullable=true)
     */
    private $order;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=true)
     */
    private $title;

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
     * Set gallery
     *
     * @param \W3build\MediaBundle\Entity\Gallery $gallery
     * @return Medium
     */
    public function setGallery(\W3build\MediaBundle\Entity\Gallery $gallery = null)
    {
        $this->gallery = $gallery;

        return $this;
    }

    /**
     * Get gallery
     *
     * @return \W3build\MediaBundle\Entity\Gallery 
     */
    public function getGallery()
    {
        return $this->gallery;
    }

    /**
     * Set order
     *
     * @param integer $order
     * @return Medium
     */
    public function setOrder($order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get order
     *
     * @return integer 
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Medium
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
     * Set title
     *
     * @param string $title
     * @return Medium
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
}
