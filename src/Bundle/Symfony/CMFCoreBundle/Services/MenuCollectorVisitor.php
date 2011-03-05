<?php
namespace Bundle\Symfony\CMFCoreBundle\Services;

use PHPCR\ItemInterface;

/**
 * visitor to collect entries for a menu hierarchy
 *
 * this visitor collects entries into a liniear list. to get the hierarchy, iterate over each level and
 * have a MenuEntryVisitor visit all children of each node, aggregate the results into nested arrays.
 *
 * @author David Buchmann <david@liip.ch>
 */
class MenuEntryVisitor extends AttributeCollectorVisitor
{
    protected $activepath;

    /**
     * @param string $titleprop property name of the title to get from the phpcr node
     * @param string $basepath path to the root of the navigation tree, will be removed from the full path
     * @param string $activepath the path to the currently opened menu item to see whether a node is ancestor of that node
     */
    public function __construct($titleprop, $basepath, $activepath)
    {
        parent::__construct($titleprop, $basepath);
        $this->activepath = $activepath;
    }

    /**
     * as defined by interface: do something with this item.
     *
     * extract path and title and info whether active (ancestor of current node) into array
     * we expect a node, will throw an exception if anything else
     */
    public function visit(ItemInterface $item)
    {
        if (! $item instanceof NodeInterface) {
            throw new Exception('Internal error: did not expect to visit a non-node object');
        }

        $path = substr($item->getPath(), $this->basepathlen);
        $title = $item->getPropertyValue($this->titleprop);
        $active = (strstr($path, $this->activepath) !== false);
        $arr = array('path' => $path, 'title' => $title, 'active' => $active, 'node' => $item);

        $this->tree[] = $arr;
    }
}