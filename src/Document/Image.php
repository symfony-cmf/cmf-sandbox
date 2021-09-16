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
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @PHPCRODM\Document(referenceable=true)
 */
class Image
{
    /**
     * @PHPCRODM\Node
     */
    public $node;

    /**
     * @Assert\NotBlank
     * @Assert\Regex("{^[a-z]+$}")
     * @PHPCRODM\Field(type="string")
     */
    public $name;

    /**
     * @Assert\NotBlank()
     *
     * @var stream
     * @PHPCRODM\Field(type="binary")
     */
    public $content;

    /**
     * @PHPCRODM\Field(type="string", multivalue=true)
     */
    public $tags;
    /**
     * to create the document at the specified location. read only for existing documents.
     *
     * @PHPCRODM\Id
     */
    protected $path;

    /**
     * Set repository path of this navigation item for creation.
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
