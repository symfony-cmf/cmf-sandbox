<?php

namespace Acme\StoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

class Tag
{
    protected $name;
    protected $slug;
    protected $createdAt;
    protected $updatedAt;
    protected $enabled;
    protected $posts;

    public function preInsert($object)
    {
        $object->setCreatedAt(new \DateTime);
        $object->setUpdatedAt(new \DateTime);
    }

    public function preUpdate($object)
    {
        $object->setUpdatedAt(new \DateTime);
    }
    /**
     * @var integer $id
     */
    private $id;


    public function __toString()
    {
        return $this->name;
    }

    public function __construct()
    {
        $this->posts = new \Doctrine\Common\Collections\ArrayCollection();
        $this->setCreatedAt(new \DateTime);
        $this->setUpdatedAt(new \DateTime);
    }
    
    /**
     * Set name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
        $this->slug = $name;
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
     * Set enabled
     *
     * @param boolean $enabled
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    }

    /**
     * Get enabled
     *
     * @return boolean 
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * Set slug
     *
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set createdAt
     *
     * @param datetime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * Get createdAt
     *
     * @return datetime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param datetime $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * Get updatedAt
     *
     * @return datetime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
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
     * Add posts
     *
     * @param Acme\StoreBundle\Entity\Post $posts
     */
    public function addPost(\Acme\StoreBundle\Entity\Post $posts)
    {
        $this->posts[] = $posts;
    }

    /**
     * Get posts
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getPosts()
    {
        return $this->posts;
    }
    /**
     * @ORM\prePersist
     */
    public function prePersist()
    {
        // Add your code here
    }
}
