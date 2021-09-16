<?php

declare(strict_types=1);

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Document;

use Doctrine\ODM\PHPCR\Mapping\Annotations as PHPCRODM;

/**
 * News content to demonstrate the RoutingAutoBundle.
 *
 * @author Daniel Leech <daniel@dantleech.com>
 *
 * @PHPCRODM\Document(referenceable=true)
 */
class DemoNewsContent extends DemoClassContent
{
    /**
     * @PHPCRODM\Field(type="date")
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
