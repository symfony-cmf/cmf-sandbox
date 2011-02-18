<?php
namespace Bundle\Symfony\CMFCoreBundle\Services;

use PHPCR\ItemVisitorInterface;
use PHPCR\ItemInterface;

/**
 * visitor to collect relative path => title into a flat array
 */
class AttributeCollectorVisitor implements ItemVisitorInterface
{
    protected $titleprop;
    protected $basepath;
    protected $basepathlen;
    protected $tree;

    /**
     * @param string $titleprop property name of the title to get from the phpcr node
     * @param string $basepath path to the root of the navigation tree, will be removed from the full path
     */
    public function __constructor($titleprop, $basepath)
    {
        $this->titleprop = $titleprop;
        $this->basepath = $basepath;
        $this->basepathlen = strlen($basepath);
        $this->tree = array();
    }

    /**
     * as defined by interface: do something with this item.
     * we expect a node, will throw an exception if anything else
     */
    public function visit(ItemInterface $item)
    {
        if (! $item instanceof NodeInterface) {
            throw new Exception('Internal error: did not expect to visit a non-node object');
        }

        $path = substr($item->getPath(), $this->basepathlen);
        $this->tree[$path] = $item->getPropertyValue($this->titleprop);
    }

    /**
     * @return the aggregated array
     */
    public function getArray()
    {
        return $this->tree;
    }
}