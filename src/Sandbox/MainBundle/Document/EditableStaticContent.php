<?php
namespace Sandbox\MainBundle\Document;

use Doctrine\ODM\PHPCR\Mapping\Annotations as PHPCRODM;
use Symfony\Cmf\Bundle\ContentBundle\Document\StaticContent;
use Liip\VieBundle\FromJsonLdInterface;

/**
 * @PHPCRODM\Document(alias="editablestatic", referenceable=true)
 */
class EditableStaticContent extends StaticContent implements FromJsonLdInterface
{
    public function fromJsonLd($data)
    {
        $this->title = $data['<http://purl.org/dc/terms/title>'];
        $this->body = $data['<http://rdfs.org/sioc/ns#content>'];
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
}
