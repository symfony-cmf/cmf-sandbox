<?php

namespace Acme\StoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

class Comment
{
    const STATUS_MODERATE   = 2;
    const STATUS_VALID   = 1;
    const STATUS_INVALID = 0;

    protected $name;
    protected $email;
    protected $url;
    protected $message;
    protected $createdAt;
    protected $updatedAt;
    protected $status = self::STATUS_VALID;
    protected $post;

    public function __toString()
    {
        return $this->name;
    }

    public static function getStatusList()
    {
        return array(
            self::STATUS_MODERATE => 'moderate',
            self::STATUS_INVALID => 'invalid',
            self::STATUS_VALID   => 'valid',
        );
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
     * @var integer $id
     */
    private $id;


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
     * Set email
     *
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set message
     *
     * @param text $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * Get message
     *
     * @return text 
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set status
     *
     * @param integer $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * Get status
     *
     * @return integer 
     */
    public function getStatus()
    {
        return $this->status;
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
     * Set post
     *
     * @param Acme\StoreBundle\Entity\Post $post
     */
    public function setPost(\Acme\StoreBundle\Entity\Post $post)
    {
        $this->post = $post;
    }

    /**
     * Get post
     *
     * @return Acme\StoreBundle\Entity\Post 
     */
    public function getPost()
    {
        return $this->post;
    }
    /**
     * @ORM\prePersist
     */
    public function prePersist()
    {
        // Add your code here
    }
}
