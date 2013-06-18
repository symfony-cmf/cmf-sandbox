<?php

namespace Sandbox\MediaBundle\Document;

use Sonata\MediaBundle\PHPCR\BaseGalleryHasMedia;

class GalleryHasMedia extends BaseGalleryHasMedia
{
    /**
     * @var string
     */
    protected $id;

    /**
     * Get id
     *
     * @return string $id
     */
    public function getId()
    {
        return $this->id;
    }
}
