<?php

namespace Sandbox\MediaBundle\Document;

use Sonata\MediaBundle\PHPCR\BaseMedia;

class Media extends BaseMedia
{
    /**
     * @var string
     */
    protected $id;

    /**
     * The basepath of the id
     *
     * @var string
     */
    protected $idPrefix;

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
     * Get the basepath of the id
     *
     * @return string
     */
    public function getIdPrefix()
    {
        return $this->idPrefix;
    }

    /**
     * Set the basepath of the id
     *
     * @param string $prefix
     */
    public function setPrefix($prefix)
    {
        $this->idPrefix = $prefix;
    }
}
