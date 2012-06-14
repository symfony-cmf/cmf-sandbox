<?php
namespace Sandbox\MainBundle\Document;

use Doctrine\ODM\PHPCR\Mapping\Annotations as PHPCRODM;
use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Cmf\Bundle\ContentBundle\Document\StaticContent;
use Symfony\Component\Validator\Constraints as Assert;

use Liip\VieBundle\FromJsonLdInterface;

/**
 * @PHPCRODM\Document(referenceable=true)
 */
class Image
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
     * @Assert\Regex("{^[a-z]+$}")
     * @PHPCRODM\String()
     */
    public $name;

    /**
     * @Assert\NotBlank()
     * @var binary
     * @PHPCRODM\Binary(name="content")
     */
    public $content;

    /**
     * @PHPCRODM\String(multivalue=true)
     */
    public $tags;

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

    public function __toString()
    {
        return $this->name;
    }
}
