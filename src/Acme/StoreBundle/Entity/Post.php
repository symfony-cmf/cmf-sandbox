<?php

namespace Acme\StoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

class Post
{
    protected $id;
    protected $title;
    protected $slug;
    protected $abstract;
    protected $content;
    protected $tags;
    protected $comments;
    protected $enabled;
    protected $publicationDateStart;
    protected $createdAt;
    protected $updatedAt;
    protected $commentsEnabled = true;
    protected $commentsCloseAt;
    protected $commentsDefaultStatus;

    public function __toString()
    {
        return $this->title;
    }
    public function __construct()
    {
        $this->tags     = new \Doctrine\Common\Collections\ArrayCollection;
        $this->comments = new \Doctrine\Common\Collections\ArrayCollection;
        $this->setCreatedAt(new \DateTime);
        $this->setUpdatedAt(new \DateTime);
    }

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
     * Set title
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
        $this->slug = $title;
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
     * Set abstract
     *
     * @param text $abstract
     */
    public function setAbstract($abstract)
    {
        $this->abstract = $abstract;
    }

    /**
     * Get abstract
     *
     * @return text 
     */
    public function getAbstract()
    {
        return $this->abstract;
    }

    /**
     * Set content
     *
     * @param text $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * Get content
     *
     * @return text 
     */
    public function getContent()
    {
        return $this->content;
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
     * Set publicationDateStart
     *
     * @param datetime $publicationDateStart
     */
    public function setPublicationDateStart($publicationDateStart)
    {
        $this->publicationDateStart = $publicationDateStart;
    }

    /**
     * Get publicationDateStart
     *
     * @return datetime 
     */
    public function getPublicationDateStart()
    {
        return $this->publicationDateStart;
    }

    /**
     * Set commentsEnabled
     *
     * @param boolean $commentsEnabled
     */
    public function setCommentsEnabled($commentsEnabled)
    {
        $this->commentsEnabled = $commentsEnabled;
    }

    /**
     * Get commentsEnabled
     *
     * @return boolean 
     */
    public function getCommentsEnabled()
    {
        return $this->commentsEnabled;
    }

    /**
     * Set commentsCloseAt
     *
     * @param datetime $commentsCloseAt
     */
    public function setCommentsCloseAt($commentsCloseAt)
    {
        $this->commentsCloseAt = $commentsCloseAt;
    }

    /**
     * Get commentsClose_at
     *
     * @return datetime 
     */
    public function getCommentsCloseAt()
    {
        return $this->commentsCloseAt;
    }

    /**
     * Set commentsDefaultStatus
     *
     * @param integer $commentsDefaultStatus
     */
    public function setCommentsDefaultStatus($commentsDefaultStatus)
    {
        $this->commentsDefaultStatus = $commentsDefaultStatus;
    }

    /**
     * Get commentsDefaultStatus
     *
     * @return integer 
     */
    public function getCommentsDefaultStatus()
    {
        return $this->commentsDefaultStatus;
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
     * Add comments
     *
     * @param Acme\StoreBundle\Entity\Comment $comments
     */
    public function addComment(\Acme\StoreBundle\Entity\Comment $comments)
    {
        $this->comments[] = $comments;
    }

    /**
     * Get comments
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Add tags
     *
     * @param Acme\StoreBundle\Entity\Tag $tags
     */
    public function addTag(\Acme\StoreBundle\Entity\Tag $tags)
    {
        $this->tags[] = $tags;
    }

    /**
     * Get tags
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getTags()
    {
        return $this->tags;
    }
    /**
     * @ORM\prePersist
     */
    public function prePersist()
    {
        // Add your code here
    }

    /**
     * @ORM\preUpdate
     */
}
