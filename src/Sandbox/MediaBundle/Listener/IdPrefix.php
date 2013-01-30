<?php

namespace Sandbox\MediaBundle\Listener;

use Doctrine\ODM\PHPCR\Event\LifecycleEventArgs;
use Sonata\MediaBundle\PHPCR\BaseGallery;
use Sonata\MediaBundle\PHPCR\BaseMedia;

/**
 * Doctrine PHPCR-ODM listener to set the idPrefix on new media and galleries
 */
class IdPrefix
{
    /**
     * The prefix to add to the media to create the repository path
     *
     * @var string
     */
    protected $mediaIdPrefix = '';

    /**
     * The prefix to add to the gallery to create the repository path
     *
     * @var string
     */
    protected $galleryIdPrefix = '';

    public function __construct($mediaPrefix, $galleryPrefix = '')
    {
        $this->mediaIdPrefix = $mediaPrefix;
        $this->galleryIdPrefix = $galleryPrefix;
    }

    /**
     * @param $prefix
     */
    public function setPrefix($prefix)
    {
        $this->idPrefix = $prefix;
    }

    public function postLoad(LifecycleEventArgs $args)
    {
        $this->updateId($args);
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $this->updateId($args);
    }

    protected function updateId(LifecycleEventArgs $args)
    {
        $doc = $args->getDocument();

        if ($doc instanceof BaseMedia) {
            $doc->setPrefix($this->mediaIdPrefix);
        }

        if ($doc instanceof BaseGallery) {
            $doc->setPrefix($this->galleryIdPrefix);
        }
    }
}