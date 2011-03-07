<?php

namespace Bundle\Symfony\CMFCoreBundle\Services;

use PHPCR\SessionInterface;
use PHPCR\NodeInterface;
use PHPCR\ItemVisitorInterface;

/**
 * everything is relative to the basepath.
 * paths must be specified without leading /
 *
 * TODO: rather have a (injected) helper to transform url paths to phpcr paths
 *
 * @author David Buchmann <david@liip.ch>
 */
class HierarchyWalker
{
    /**
     * reference to the phpcr session
     * @var SessionInterface
     */
    protected $session;

    /**
     * @var \Bundle\DoctrinePHPCRBundle\JackalopeLoader
     */
    protected $jackalope = null;


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
    public function __construct($jackalope, $basepath, $titleprop = 'name')
    {
        $this->jackalope = $jackalope;
        $this->session = $jackalope->getSession();
        $this->basepath = $basepath;
        $this->titleprop = $titleprop;
        $this->rootnode = $this->session->getNode($basepath);
        if ($this->rootnode == null) {
            throw new Exception("Not found the node at $basepath");
        }
    }

    /**
     * get the children of a node identified by path
     * @param string $path the path relative to basepath
     * @return array with relpath => title for each child of the node at $path
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
     * @param ItemVisitorInterface $visitor the visitor to look at the nodes
     */
    public function visitChildren($path, ItemVisitorInterface $visitor)
    {
        $node = $this->rootnode->getNode($path);
        foreach($node as $child) {
            $child->accept($visitor);
        }
    }

    /**
     * get all ancestors from basepath down to the parent of the node identified by path
     *
     * @param string $path the path relative to basepath
     * @return array with relpath => title, starting with basepath, ending with the parent of path
     */
    public function getAncestors($path)
    {
        $visitor = new AttributeCollectorVisitor($this->titleprop, $this->basepath);
        $this->visitAncestors($path, $visitor);
        return $visitor->getArray();
    }

    /**
     * let the visitor visit the ancestors from basepath down to the parent of the node identified by path
     *
     * @param string $path the path relative to basepath
     * @param ItemVisitorInterface $visitor the visitor to look at the nodes
     */
    public function visitAncestors($path, ItemVisitorInterface $visitor)
    {
        $node = $this->rootnode->getNode($path);
        $i = $this->rootnode->getDepth();
        while(($ancestor = $node->getAncestor($i++)) != $node) {
            $ancestor->accept($visitor);
        }
    }

    /**
     * get a menu tree leading to this path.
     *
     * using the depth parameter, you can preload children of other items not in the breadcrumb
     *
     * root => l1
     *      => l1
     *      => l1 (active, children)
     *         => l2
     *         => l2
     *      => l1
     *
     * @param string $path the path relative to basepath
     * @param int $depth depth to follow non-active node children
     *
     * @return array structure with entries for each node: title, path, active (parent of $path or $path itselves), children (array, empty array on no children. only set if active node or within depth.)
     */
    public function getMenu($path, $depth=0)
    {
        //walk the tree ourselves, use the visitor to collect the children
        $visitor = new MenuCollectorVisitor($this->titleprop, $this->basepath, $path);
        $this->rootnode->accept($visitor);
        $tree = $visitor->getArray();
        $tree = $tree[0]; //visitor just was at the root node
        $tree['children'] = $this->getMenuRecursive($tree, $depth, 0);
        return $tree;
    }

    /**
     * iterate over the menu tree recursively, starting with the children of a record from the MenuCollectorVisitor
     *
     * @param array $record as returned by MenuCollectorVisitor
     * @return nested array of all children of this node and their children down the active path and others down to $depth
     */
    protected function getMenuRecursive($record, $path, $depth, $curdepth)
    {
        $visitor = new MenuCollectorVisitor($this->titleprop, $this->basepath, $path);
        foreach($record['node'] as $child) {
            //iterate over that node's children
            $child->accept($visitor);
        }
        $list = $visitor->getArray();
        foreach($list as $record) {
            if ($record['active']) {
                $record['children'] = $this->getMenuRecursive($record, $path, $depth, 0);
            } elseif ($curdepth < $depth) {
                $record['children'] = $this->getMenuRecursive($record, $path, $depth, $curdepth + 1);
            }
        }
        return $list;
    }
}
