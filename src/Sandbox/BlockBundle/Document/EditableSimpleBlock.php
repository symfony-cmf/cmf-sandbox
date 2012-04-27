<?php

namespace Sandbox\BlockBundle\Document;

use Doctrine\ODM\PHPCR\Mapping\Annotations as PHPCRODM,
    Symfony\Cmf\Bundle\BlockBundle\Document\SimpleBlock,
    Liip\VieBundle\FromJsonLdInterface;

/**
 * Editable block with hypertext and a title
 *
 * @PHPCRODM\Document(referenceable=true)
 */
class EditableSimpleBlock extends SimpleBlock implements FromJsonLdInterface
{

    public function fromJsonLd($data)
    {
        $this->setTitle($data['<http://purl.org/dc/terms/title>']);
        $this->setContent($data['<http://rdfs.org/sioc/ns#content>']);
    }

}