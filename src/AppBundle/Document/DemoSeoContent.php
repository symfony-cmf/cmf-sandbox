<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2017 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Document;

use Doctrine\ODM\PHPCR\Mapping\Annotations as PHPCRODM;
use Symfony\Cmf\Bundle\ContentBundle\Doctrine\Phpcr\StaticContent;
use Symfony\Cmf\Bundle\SeoBundle\Doctrine\Phpcr\SeoMetadata;
use Symfony\Cmf\Bundle\SeoBundle\SeoAwareInterface;

/**
 * That example class uses the extractors for the creation of the SeoMetadata.
 *
 * @PHPCRODM\Document(referenceable=true, translator="child")
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class DemoSeoContent extends StaticContent implements SeoAwareInterface
{
    /**
     * @var SeoMetadata
     *
     * @PHPCRODM\Child
     */
    protected $seoMetadata;

    public function __construct()
    {
        $this->seoMetadata = new SeoMetadata();
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    public function getSeoMetadata()
    {
        return $this->seoMetadata;
    }

    /**
     * {@inheritdoc}
     */
    public function setSeoMetadata($seoMetadata)
    {
        $this->seoMetadata = $seoMetadata;
    }
}
