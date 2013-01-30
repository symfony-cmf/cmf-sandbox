<?php

namespace Sandbox\BannerBundle\Document;

use Doctrine\ODM\PHPCR\Mapping\Annotations as PHPCRODM;
use Sandbox\BannerBundle\Document\BannerBlockInterface;
use Sandbox\MediaBundle\Document\Media;

/**
 * Document that contains a Media and additional banner information
 *
 * @PHPCRODM\Document(referenceable=true)
 */
class BannerImage
{
    /**
     * @PHPCRODM\Id(strategy="parent")
     */
    protected $id;

    /**
     * @PHPCRODM\Nodename
     */
    protected $name;

    /**
     * @PHPCRODM\ParentDocument
     */
    protected $parentDocument;

    /**
     * @PHPCRODM\String
     */
    protected $title;

    /**
     * @PHPCRODM\String
     */
    protected $description;

    /**
     * @PHPCRODM\String
     */
    protected $url;

    /**
     * @PHPCRODM\ReferenceOne(strategy="weak", targetDocument="Sandbox\MediaBundle\Document\Media")
     */
    protected $image;

    /**
     * @var integer
     */
    protected $position;

    /**
     * @PHPCRODM\Date()
     */
    protected $createdAt;

    /**
     * @PHPCRODM\Date()
     */
    protected $updatedAt;

    public function __toString()
    {
        return $this->getTitle() ? $this->getTitle() : $this->getName();
    }

    /**
     * Get id
     *
     * @return string $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set parent document
     *
     * @param object $parent
     */
    public function setParentDocument(BannerBlockInterface $parent)
    {
        $this->parentDocument = $parent;
    }

    /**
     * Get the parent document
     *
     * @return object|null $document
     */
    public function getParentDocument()
    {
        return $this->parentDocument;
    }

    /**
     * Set title
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
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
     */
    public function setDescription($description)
    {
        $this->description = $description;
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
     * Set url
     *
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set image
     *
     * @param Media $image
     */
    public function setImage(Media $image)
    {
        $this->image = $image;
    }

    /**
     * Get image
     *
     * @return Media
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set position
     *
     * @param integer $position
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }

    /**
     * Get position
     *
     * @return integer
     */
    public function getPosition()
    {
        return $this->position;

//        $siblings = $this->getParentDocument()->getItems();
//
//        return array_search($siblings->indexOf($this), $siblings->getKeys());
    }

    /**
     * Set createdAt
     *
     * @PHPCRODM\PrePersist()
     * @param \Datetime $createdAt
     */
    public function setCreatedAt(\DateTime $createdAt = null)
    {
        $this->createdAt = is_null($createdAt) ? new \DateTime() : $createdAt;
    }

    /**
     * Get createdAt
     *
     * @return \Datetime $createdAt
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @PHPCRODM\PrePersist()
     * @PHPCRODM\PreUpdate()
     * @param \Datetime $updatedAt
     */
    public function setUpdatedAt(\DateTime $updatedAt = null)
    {
        $this->updatedAt = is_null($updatedAt) ? new \DateTime() : $updatedAt;
    }

    /**
     * Get updatedAt
     *
     * @return \Datetime $updatedAt
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
}
