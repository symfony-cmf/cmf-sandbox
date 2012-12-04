<?php

namespace Sandbox\MainBundle\Document;

use Doctrine\ODM\PHPCR\Mapping\Annotations as PHPCRODM;

use Symfony\Cmf\Bundle\SimpleCmsBundle\Document\Page;

/**
 * @PHPCRODM\Document(referenceable=true)
 */
class MenuItem extends Page
{
}
