<?php

namespace Sandbox\MainBundle\Document;

/**
 * @phpcr:Document(alias="page")
 */
class Page
{
    /**
     * @validation:NotBlank
     * @validation:Regex("{^[a-z]+$}")
     * @phpcr:String()
     */
    public $name;

    /**
     * @validation:NotBlank
     * @phpcr:String()
     */
    public $title;

    /**
     * @phpcr:String()
     */
    public $content;

}
