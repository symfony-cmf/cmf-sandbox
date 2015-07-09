<?php


namespace AppBundle\Document;

use Doctrine\ODM\PHPCR\Mapping\Annotations as PHPCR;
use Symfony\Cmf\Bundle\ContentBundle\Doctrine\Phpcr\StaticContent;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\PHPCR\ChildrenCollection;

/**
 * @PHPCR\Document()
 */
class BaseStaticContent extends StaticContent {

    /**
     * @PHPCR\Children()
     */
    public $children;

    public function __construct($name = null)
    {
        parent::__construct();
        $this->children = new ArrayCollection();
    }

    /**
     * Get children
     *
     * @return ArrayCollection|ChildrenCollection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Set children
     *
     * @param ChildrenCollection $children
     *
     * @return ChildrenCollection
     */
    public function setChildren(ChildrenCollection $children)
    {
        return $this->children = $children;
    }

    /**
     * Add a child to this container
     *
     * @param mixed $child
     * @param string $key the collection index name to use in the
     *                              child collection. if not set, the child
     *                              will simply be appended at the end.
     *
     * @return boolean Always true
     */
    public function addChild($child, $key = null)
    {
        if ($key != null) {

            $this->children->set($key, $child);

            return true;
        }

        return $this->children->add($child);
    }

    /**
     * Alias to addChild to make the form layer happy.
     *
     * @param mixed $children
     *
     * @return boolean
     */
    public function addChildren($children)
    {
        return $this->addChild($children);
    }

    /**
     * Remove a child from this container.
     *
     * @param  mixed $child
     *
     * @return $this
     */
    public function removeChild($child)
    {
        $this->children->removeElement($child);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function hasChildren()
    {
        return count($this->children) > 0;
    }
}