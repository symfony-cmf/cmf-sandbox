<?php

namespace Sandbox\MainBundle\DataFixtures\PHPCR;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use PHPCR\Util\NodeHelper;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\Yaml\Parser;

use Sandbox\MainBundle\Document\EditableStaticContent;

class LoadStaticPageData extends ContainerAware implements FixtureInterface, OrderedFixtureInterface
{
    public function getOrder()
    {
        return 5;
    }

    public function load(ObjectManager $manager)
    {
        $session = $manager->getPhpcrSession();
        $basepath = $this->container->getParameter('symfony_cmf_content.static_basepath');

        NodeHelper::createPath($session, $basepath);

        $yaml = new Parser();
        $data = $yaml->parse(file_get_contents(__DIR__ . '/../static/page.yml'));

        foreach ($data['static'] as $overview) {
            $path = $basepath . '/' . $overview['name'];
            $page = $manager->find(null, $path);
            if (! $page) {
                $class = isset($overview['class']) ? $overview['class'] : 'Sandbox\\MainBundle\\Document\\EditableStaticContent';
                $page = new $class();
                $page->setPath($path);
                $manager->persist($page);
            }
            $page->setName($overview['name']);

            if (is_array($overview['title'])) {
                foreach ($overview['title'] as $locale => $title) {
                    $page->setTitle($title);
                    $page->setContent($overview['content'][$locale]);
                    $manager->bindTranslation($page, $locale);
                }
            } else {
                $page->setTitle($overview['title']);
                $page->setContent($overview['content']);
            }
            if (isset($overview['blocks'])) {
                foreach ($overview['blocks'] as $name => $block) {
                    $this->loadBlock($manager, $page, $name, $block);
                }
            }
        }

        $manager->flush(); //to get ref id populated
    }

    /**
     * Load a block from the fixtures and create / update the node. Recurse if there are children.
     *
     * @param $manager the document manager
     * @param string $parentPath the parent of the block
     * @param string $name the name of the block
     * @param array block the block definition
     */
    private function loadBlock($manager, $parent, $name, $block)
    {
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
            foreach ($block['children'] as $childName => $child) {
                $this->loadBlock($manager, $document, $childName, $child);
            }
        }
    }

    private function getIdentifier($manager, $document)
    {
        $class = $manager->getClassMetadata(get_class($document));
        return $class->getIdentifierValue($document);
    }

}
