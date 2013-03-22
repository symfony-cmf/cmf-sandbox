<?php

namespace Sandbox\MainBundle\Document;

use Doctrine\ODM\PHPCR\Mapping\Annotations as PHPCRODM;

use Symfony\Cmf\Bundle\ContentBundle\Document\MultilangStaticContent;

/**
 * @PHPCRODM\Document
 */
class NewsArticle extends MultilangStaticContent
{
}
