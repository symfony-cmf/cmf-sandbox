<?php
namespace Sandbox\MainBundle\Document;

use Doctrine\ODM\PHPCR\Mapping\Annotations as PHPCRODM;
use Symfony\Cmf\Bundle\MultilangContentBundle\Document\MultilangStaticContent;
use Liip\VieBundle\FromJsonLdInterface;

/**
 * @PHPCRODM\Document(referenceable=true,translator="child")
 */
class EditableStaticContent extends MultilangStaticContent implements FromJsonLdInterface
{
    /**
     * @PHPCRODM\String(multivalue=true)
     */
    public $tags;

    public function fromJsonLd($data)
    {
        $this->title = isset($data['<dcterms:title>']) ? $data['<dcterms:title>'] : $data['<http://purl.org/dc/terms/title>'];
        $this->body = isset($data['<sioc:content>']) ? $data['<sioc:content>'] : $data['<http://rdfs.org/sioc/ns#content>'];
        $this->tags = $data['<http://purl.org/dc/elements/1.1/subject>'];
    }

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

    public function getContent()
    {
        return $this->body;
    }

    public function setContent($content)
    {
        $this->body = $content;
    }

    public function __toString()
    {
        return $this->title;
    }
}
