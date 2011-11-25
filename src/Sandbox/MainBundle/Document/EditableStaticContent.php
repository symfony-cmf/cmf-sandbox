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
        $this->content = $data['<http://rdfs.org/sioc/ns#content>'];
    }
}
