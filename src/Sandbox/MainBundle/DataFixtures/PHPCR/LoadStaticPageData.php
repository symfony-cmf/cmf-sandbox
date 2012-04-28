<?php

namespace Sandbox\MainBundle\DataFixtures\PHPCR;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\Yaml\Parser;

use Sandbox\MainBundle\Document\EditableStaticContent;

class LoadStaticPageData implements FixtureInterface, OrderedFixtureInterface, ContainerAwareInterface
{
    protected $session;

    protected $container;

    public function setContainer(ContainerInterface $container = null)
    {
        if (! $container) {
            throw new \Exception("This does not work without container");
        }
        $this->container = $container;
        $this->session = $this->container->get('doctrine_phpcr.default_session'); // FIXME: should get this from manager in load, not necessarily the default
    }

    public function getOrder() {
        return 5;
    }

    public function load(ObjectManager $manager)
    {
        if (! $this->container) {
            throw new \Exception("This does not work without container");
        }
        $basepath = $this->container->getParameter('symfony_cmf_content.static_basepath');

        $this->createPath($basepath);

        $yaml = new Parser();
        $data = $yaml->parse(file_get_contents(__DIR__ . '/../static/page.yml'));

        foreach($data['static'] as $overview) {
            $path = $basepath . '/' . $overview['name'];
            $page = $manager->find(null, $path);
            if (! $page) {
                $class = isset($overview['class']) ? $overview['class'] : 'Sandbox\\MainBundle\\Document\\EditableStaticContent';
                $page = new $class();
                $page->setPath($path);
                $manager->persist($page);
            }
            $page->name = $overview['name'];

            if (is_array($overview['title'])) {
                foreach($overview['title'] as $locale => $title) {
                    $page->title = $title;
                    $page->body = $overview['content'][$locale];
                    $manager->bindTranslation($page, $locale);
                }
            } else {
                $page->title = $overview['title'];
                $page->body = $overview['content'];
            }
            if (isset($overview['blocks'])) {
                foreach($overview['blocks'] as $name => $block) {
                    $this->loadBlock($manager, $page, $name, $block);
                }
            }
        }

        $manager->flush(); //to get ref id populated
    }

    /**
     * Create a node and it's parents, if necessary.  Like mkdir -p.
     *
     * TODO: clean this up once the id generator stuff is done as intended
     *
     * @param string $path  full path, like /cms/navigation/main
     * @return Node the (now for sure existing) node at path
     */
    public function createPath($path)
    {
        $current = $this->session->getRootNode();

        $segments = preg_split('#/#', $path, null, PREG_SPLIT_NO_EMPTY);
        foreach ($segments as $segment) {
            if ($current->hasNode($segment)) {
                $current = $current->getNode($segment);
            } else {
                $current = $current->addNode($segment);
            }
        }

        return $current;
    }

    /**
     * Load a block from the fixtures and create / update the node. Recurse if there are children.
     *
     * @param $manager the document manager
     * @param string $parentPath the parent of the block
     * @param string $name the name of the block
     * @param array block the block definition
     */
    private function loadBlock($manager, $parent, $name, $block) {
        $className = $block['class'];
        $document = $manager->find(null, $this->getIdentifier($manager, $parent) . '/' . $name);
        $class = $manager->getClassMetadata($className);
        if ($document && get_class($document) != $className) {
            $manager->remove($document);
            $document = null;
        }
        if (!$document) {
            $document = $class->newInstance();

            // $document needs to be an instance of BaseBlock ...
            $document->setParentDocument($parent);
            $document->setName($name);
            $manager->persist($document);
        }

        if ($className == 'Symfony\Cmf\Bundle\BlockBundle\Document\ReferenceBlock') {
            $referencedBlock = $this->container->get('symfony_cmf.block.service')->findByName($block['referencedBlock']);
            $document->setReferencedBlock($referencedBlock);
        } else if ($className == 'Symfony\Cmf\Bundle\BlockBundle\Document\ActionBlock') {
            $document->setActionName($block['actionName']);
        }

        // set properties
        if (isset($block['properties'])) {
            foreach ($block['properties'] as $propName => $prop) {
                $class->reflFields[$propName]->setValue($document, $prop);
            }
        }
        // create children
        if (isset($block['children'])) {
            foreach($block['children'] as $childName => $child) {
                $this->loadBlock($manager, $document, $childName, $child);
            }
        }
    }

    private function getIdentifier($manager, $document) {
        $class = $manager->getClassMetadata(get_class($document));
        return $class->getIdentifierValue($document);
    }

}
