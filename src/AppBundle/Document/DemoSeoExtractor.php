<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2015 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Document;

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
class DemoSeoExtractor extends DemoSeoContent implements TitleReadInterface, DescriptionReadInterface, OriginalUrlReadInterface, KeywordsReadInterface
{
    /**
     * {@inheritdoc}
     */
    public function getSeoTitle()
    {
        return $this->getTitle();
    }

    /**
     * {@inheritdoc}
     */
    public function getSeoDescription()
    {
        return substr($this->getBody(), 0, 200).' ...';
    }

    /**
     * {@inheritdoc}
     */
    public function getSeoOriginalUrl()
    {
        return '/home';
    }

    /**
     * {@inheritdoc}
     */
    public function getSeoKeywords()
    {
        return $this->getTags();
    }
}
