<?php

namespace Sandbox\BannerBundle\Document;

use Doctrine\ODM\PHPCR\Mapping\Annotations as PHPCRODM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Cmf\Bundle\BlockBundle\Document\BaseBlock;
use Sandbox\BannerBundle\Document\BannerImage;

/**
 * Banner block that contains BannerImage documents
 *
 * @PHPCRODM\Document(referenceable=true)
 */
class ImageBlock extends BaseBlock implements BannerBlockInterface
{
    /** @PHPCRODM\Children() */
    protected $items;

    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    public function getType()
    {
        return 'sandbox_banner.block.image';
    }

    /**
     * {@inheritDoc}
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * {@inheritDoc}
     */
    public function setItems(Collection $items)
    {
        $this->items = $items;
    }

    /**
     * {@inheritDoc}
     */
    public function addItem(BannerImage $item, $key = null)
    {
        if (is_null($this->items)) {
            $this->items = new ArrayCollection();
        }

        $item->setParentDocument($this);

        // set nodename of BannerImage
        if (!$item->getName()) {
            $item->setName(
                'image'.($this->items->count() + 1)
            );
        }

        if ($key !== null) {
            return $this->items->set($key, $item);
        }

        return $this->items->add($item);
    }

    /**
     * {@inheritDoc}
     */
    public function removeItem(BannerImage $item, $key = null)
    {
        if (is_null($this->items)) {
            return false;
        }

        if ($key !== null) {
            return $this->items->remove($key);
        }

        return $this->items->removeElement($item);
    }

    /**
     * Reorders BannerImage items based on their position
     *
     * @PHPCRODM\PrePersist()
     * @PHPCRODM\PreUpdate()
     */
    public function reorderItems()
    {
        if ($this->getItems() && $this->getItems() instanceof \IteratorAggregate) {

            // reorder
            $iterator = $this->getItems()->getIterator();

            $iterator->uasort(function ($a, $b) {
                if ($a->getPosition() === $b->getPosition()) {
                    return 0;
                }

                return $a->getPosition() > $b->getPosition() ? 1 : -1;
            });

            $this->setItems(
                new ArrayCollection(iterator_to_array($iterator))
            );
        }
    }
}
