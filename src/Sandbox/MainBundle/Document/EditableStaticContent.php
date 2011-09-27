<?php
namespace Sandbox\MainBundle\Document;

use Doctrine\ODM\PHPCR\Mapping\Annotations as PHPCRODM;
use Symfony\Cmf\Bundle\ContentBundle\Document\StaticContent;
use Liip\VieBundle\FromJsonLdInterface;

/**
 * @PHPCRODM\Document(alias="editablestatic")
 */
class EditableStaticContent extends StaticContent implements FromJsonLdInterface
{
    public function fromJsonLd($data)
    {
        $this->title = $data['dcterms:title'];
        $this->content = $data['sioc:content'];
    }
}
