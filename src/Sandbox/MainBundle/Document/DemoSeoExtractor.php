<?php

namespace Sandbox\MainBundle\Document;

use Doctrine\ODM\PHPCR\Mapping\Annotations as PHPCRODM;
use Symfony\Cmf\Bundle\SeoBundle\Extractor\DescriptionReadInterface;
use Symfony\Cmf\Bundle\SeoBundle\Extractor\KeywordsReadInterface;
use Symfony\Cmf\Bundle\SeoBundle\Extractor\OriginalUrlReadInterface;
use Symfony\Cmf\Bundle\SeoBundle\Extractor\TitleReadInterface;

/**
 * That example class uses the extractors for the creation of the SeoMetadata.
 *
 * @PHPCRODM\Document(referenceable=true)
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class DemoSeoExtractor extends DemoSeoContent implements
    TitleReadInterface,
    DescriptionReadInterface,
    OriginalUrlReadInterface,
    KeywordsReadInterface
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
