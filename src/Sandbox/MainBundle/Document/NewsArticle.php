<?php

namespace Sandbox\MainBundle\Document;

use Doctrine\ODM\PHPCR\Mapping\Annotations as PHPCRODM;

use Symfony\Cmf\Bundle\ContentBundle\Document\StaticContent;

/**
 * @PHPCRODM\Document
 */
class NewsArticle extends StaticContent
{
}
