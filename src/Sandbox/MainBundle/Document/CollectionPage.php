<?php

namespace Sandbox\MainBundle\Document;

use Doctrine\ODM\PHPCR\Mapping\Annotations as PHPCRODM;

use Symfony\Cmf\Bundle\ContentBundle\Document\StaticContent;

/**
 * @PHPCRODM\Document
 */
class CollectionPage extends StaticContent
{
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
}
