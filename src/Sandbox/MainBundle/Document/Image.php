<?php
namespace Sandbox\MainBundle\Document;

use Doctrine\ODM\PHPCR\Mapping\Annotations as PHPCRODM;
use Symfony\Cmf\Bundle\ContentBundle\Document\StaticContent;
use Symfony\Component\Validator\Constraints as Assert;
use Liip\VieBundle\FromJsonLdInterface;

/**
 * @PHPCRODM\Document(alias="image", referenceable=true)
 */
class Image implements FromJsonLdInterface
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
}
