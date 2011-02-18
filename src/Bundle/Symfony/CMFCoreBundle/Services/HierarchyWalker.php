<?php

namespace Bundle\Symfony\CMFCoreBundle\Services;

use PHPCR\SessionInterface;
use PHPCR\NodeInterface;
use PHPCR\ItemVisitor;

/**
 * everything is relative to the basepath.
 * paths must be specified without leading /
 */
class HierarchyWalker
{
    /**
     * reference to the phpcr session
     * SessionInterface
     */
    protected $session;

    /**
     * string path to the root of this navigation tree
     */
    protected $basepath;

    /**
     * node object that is the root of this navigation tree
     * NodeInterface
     */
    protected $rootnode;

    /**
     * title property name for the node visitor
     */
    protected $titleprop;

    /**
     * @param SessionInterface $session the phpcr session
     * @param string $basepath phpcr path to the root of the navigation tree
     * @param string $titleprop name of the title property to be returned along with the hierarchy. optional, if you always specify your own ItemVisitor
     */
    public function __construct(SessionInterface $session, $basepath, $titleprop = '')
    {
        $this->session = $session;
        $this->basepath = $basepath;
        $this->titleprop = $titleprop;
        $this->rootnode = $session->getNode($basepath);
    }

    /**
     * get the children of a node identified by path
     * @param string $path the path relative to basepath
     */
    public function getChildList($path)
    {
        $visitor = new AttributeCollectorVisitor($this->titleprop, $this->basepath);
        $this->visitChildren($path, $visitor);
        return $visitor->getArray();
    }

    /**
     * let the visitor visit all children of $path
     * @param string $path the relative path to the node
     * @param ItemVisitor $visitor the visitor to look at the nodes
     */
    public function visitChildren($path, ItemVisitor $visitor)
    {
        $node = $this->rootnode->getNode($path);
        foreach($node as $child) {
            $child->accept($visitor);
        }
    }

    /**
     * get all parents from basepath down to the parent of the node identified by path
     *
     * @param string $path the path relative to basepath
     * @return array with relpath => title, starting with basepath, ending with the parent of path
     */
    public function getParents($path)
    {
        $visitor = new AttributeCollectorVisitor($this->titleprop, $this->basepath);
        $this->visitParents($path, $visitor);
        return $visitor->getArray();
    }

    /**
     * let the visitor visit the parents from basepath down to the parent of the node identified by path
     *
     * @param string $path the path relative to basepath
     * @param ItemVisitor $visitor the visitor to look at the nodes
     */
    public function visitParents($path, ItemVisitor $visitor)
    {
        $node = $this->rootnode->getNode($path);

        $parents = array();
        $i = $this->rootnode->getDepth();
        while(($parent = $node->getAncestor($i++)) != $node) {
            $parent->accept($visitor);
        }
    }
}
