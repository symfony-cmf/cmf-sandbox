<?php

/*
 * This file is part of the CMF Sandbox package.
 *
 * (c) 2011-2015 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Document;

use Doctrine\ODM\PHPCR\Mapping\Annotations as PHPCRODM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Cmf\Component\Routing\RouteReferrersReadInterface;

/**
 * A document that we map to a template to be used with the generic content
 * controller
 *
 * @PHPCRODM\Document(referenceable=true)
 */
class DemoTemplateContent implements RouteReferrersReadInterface
{
    /**
     * to create the document at the specified location. read only for existing documents.
     *
     * @PHPCRODM\Id
     */
    protected $path;

    /**
     * @PHPCRODM\Node
     */
    public $node;

    /**
     * @Assert\NotBlank
     * @PHPCRODM\String()
     */
    public $name;

    /**
     * @Assert\NotBlank
     * @PHPCRODM\String()
     */
    public $title;

    /**
     * @Assert\NotBlank
     * @PHPCRODM\String()
     */
    public $body;

    /**
     * @PHPCRODM\Referrers(referringDocument="Symfony\Cmf\Bundle\RoutingBundle\Doctrine\Phpcr\Route", referencedBy="content")
     */
    public $routes;

    /**
     * Set repository path of this navigation item for creation
     */
    public function setPath($path)
    {
      $this->path = $path;
    }

    public function getPath()
    {
      return $this->path;
    }

    public function getContent()
    {
        return $this->body;
    }

    public function setContent($content)
    {
        $this->body = $content;
    }

    /**
     * @return array of route objects that point to this content
     */
    public function getRoutes()
    {
        return $this->routes->toArray();
    }
}
