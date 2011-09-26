<?php
namespace Sandbox\MainBundle\Document;

class EditableStaticContent extends Symfony\Cmf\Bundle\ContentBundle\Document\StaticContent implements FromJsonLdInterface
{
    public function fromJsonLd($data)
    {
        $this->title = $data['title'];
        $this->content = $data['content'];
    }
}
