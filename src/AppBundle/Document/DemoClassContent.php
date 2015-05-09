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
 * A document that we map to a controller
 *
 * @PHPCRODM\Document(referenceable=true)
 */
class DemoClassContent implements RouteReferrersReadInterface
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
     * @PHPCRODM\Parentdocument()
     */
    public $parentDocument;

    /**
     * @Assert\NotBlank
     * @PHPCRODM\Nodename()
     */
    protected $name;

    /**
     * @Assert\NotBlank
     * @PHPCRODM\String()
     */
    protected $title;

    /**
     * @Assert\NotBlank
     * @PHPCRODM\String()
     */
    protected $body;

    /**
     * @PHPCRODM\Referrers(referringDocument="Symfony\Cmf\Bundle\RoutingBundle\Doctrine\Phpcr\Route", referencedBy="content")
     */
    public $routes;

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

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
    public function setParentDocument($parent)
    {
        $this->parentDocument = $parent;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function setBody($content)
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
