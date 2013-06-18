<?php

namespace Sandbox\BannerBundle\Document;

use Doctrine\Common\Collections\Collection;
use Sandbox\BannerBundle\Document\BannerImage;

interface BannerBlockInterface
{
    /**
     * Get items
     *
     * @return Collection
     */
    function getItems();

    /**
     * Set items
     *
     * @param Collection $items
     */
    function setItems(Collection $items);

    /**
     * Add a BannerImage to this block
     *
     * @param  BannerImage $item
     * @param  string $key OPTIONAL
     * @return boolean
     */
    function addItem(BannerImage $item, $key = null);

    /**
     * Remove a BannerImage from this block
     *
     * @param BannerImage $item
     * @param null $key
     * @return boolean
     */
    function removeItem(BannerImage $item, $key = null);
}
