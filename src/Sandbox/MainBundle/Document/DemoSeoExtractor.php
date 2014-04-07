<?php

namespace Sandbox\MainBundle\Document;

use Doctrine\ODM\PHPCR\Mapping\Annotations as PHPCRODM;
use Symfony\Cmf\Bundle\SeoBundle\Extractor\SeoDescriptionReadInterface;
use Symfony\Cmf\Bundle\SeoBundle\Extractor\SeoKeywordsReadInterface;
use Symfony\Cmf\Bundle\SeoBundle\Extractor\SeoOriginalUrlReadInterface;
use Symfony\Cmf\Bundle\SeoBundle\Extractor\SeoTitleReadInterface;

/**
 * That example class uses the extractors for the creation of the SeoMetadata.
 *
 * @PHPCRODM\Document(referenceable=true)
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class DemoSeoExtractor extends DemoSeoContent implements
    SeoTitleReadInterface,
    SeoDescriptionReadInterface,
    SeoOriginalUrlReadInterface,
    SeoKeywordsReadInterface
{
    /**
     * {@inheritDoc}
     */
    public function getSeoTitle()
    {
        return $this->getTitle();
    }

    /**
     * {@inheritDoc}
     */
    public function getSeoDescription()
    {
        return substr($this->getBody(), 0, 200).' ...';
    }

    /**
     * {@inheritDoc}
     */
    public function getSeoOriginalUrl()
    {
        return "/home";
    }

    /**
     * {@inheritDoc}
     */
    public function getSeoKeywords()
    {
        return $this->getTags();
    }
}
