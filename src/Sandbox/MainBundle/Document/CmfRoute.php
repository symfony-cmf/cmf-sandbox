<?php

namespace Sandbox\MainBundle\Document;

use Doctrine\ODM\PHPCR\Mapping\Annotations as PHPCRODM;

use Symfony\Cmf\Bundle\RoutingExtraBundle\Document\Route;

/**
 * @PHPCRODM\Document
 */
class CmfRoute extends Route
{
    /**
     * Set the _locale requirement and the default _locale
     * @param array $locale
     */
    public function setLocale($locale) {
        parent::setDefault('_locale', $locale);
        parent::setRequirement('_locale', $locale);
    }

    /**
     * Get the default _locale for this route
     * @return string
     */
    public function getLocale() {
        return parent::getDefault('_locale');
    }

}
