<?php

namespace Sandbox\MainBundle\Document;

use Doctrine\ODM\PHPCR\Mapping\Annotations as PHPCRODM;

use Symfony\Cmf\Bundle\ContentBundle\Document\StaticContent;

/**
 * @PHPCRODM\Document(translator="attribute")
 */
class CollectionPage extends StaticContent
{
    /**
     * @PHPCRODM\Locale
     */
    protected $locale;

    /**
     * @PHPCRODM\String(translated=true)
     */
    protected $title;

    /**
     * @PHPCRODM\String(translated=true)
     */
    protected $body;

    /**
     * @PHPCRODM\Children
     */
    protected $children;

    public function getChildren()
    {
        return $this->children;
    }

    public function setChildren($children)
    {
        $this->children = $children;
    }

    public function getLocale()
    {
        return $this->locale;
    }

    public function setLocale($locale)
    {
        $this->locale = $locale;
    }
}
