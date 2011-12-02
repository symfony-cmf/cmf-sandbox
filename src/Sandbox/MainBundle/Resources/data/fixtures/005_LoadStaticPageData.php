<?php

namespace Sandbox\MainBundle\Resources\data\fixtures;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

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

    public function load($manager)
    {
        $contentdocument =  $this->container->getParameter('symfony_cmf_content.document');
        $basepath = $this->container->getParameter('symfony_cmf_content.static_basepath');

        $this->createPath($basepath);

        $yaml = new Parser();
        $data = $yaml->parse(file_get_contents(__DIR__ . '/static/page.yml'));

        $overview = $data['static'];
        foreach($data['static'] as $overview) {
            $path = $basepath . '/' . $overview['name'];
            $page = $manager->find($contentdocument, $path);
            if (! $page) {
                $page = new EditableStaticContent();
                $page->setPath($path);
                $manager->persist($page);
            }
            $page->name = $overview['name'];
            $page->title = $overview['title'];
            $page->content = $overview['content'];
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
}
