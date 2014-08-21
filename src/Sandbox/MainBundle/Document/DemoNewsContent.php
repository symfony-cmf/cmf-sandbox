<?php

namespace Sandbox\MainBundle\Document;

use Doctrine\ODM\PHPCR\Mapping\Annotations as PHPCRODM;

/**
 * News content to demonstrate the RoutingAutoBundle
 *
 * @author Daniel Leech <daniel@dantleech.com>
 *
 * @PHPCRODM\Document(referenceable=true)
 */
class DemoNewsContent extends DemoClassContent
{
    /**
     * @PHPCRODM\Date()
     */
    protected $date;

    public function __construct()
    {
        $this->date = new \DateTime();
    }

    public function setTitle($title)
    {
        parent::setTitle($title);
        $this->setName($title);
    }

    public function getDate() 
    {
        return $this->date;
    }
    
    public function setDate($date)
    {
        $this->date = $date;
    }
}
