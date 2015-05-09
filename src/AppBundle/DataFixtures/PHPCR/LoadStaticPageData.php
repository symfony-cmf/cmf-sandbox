<?php

/*
 * This file is part of the CMF Sandbox package.
 *
 * (c) 2011-2015 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\DataFixtures\PHPCR;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Doctrine\ODM\PHPCR\DocumentManager;
use PHPCR\Util\NodeHelper;

use AppBundle\Document\DemoSeoContent;
use Symfony\Cmf\Bundle\SeoBundle\Doctrine\Phpcr\SeoMetadata;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\Yaml\Parser;

class LoadStaticPageData extends ContainerAware implements FixtureInterface, OrderedFixtureInterface
{
    public function getOrder()
    {
        return 5;
    }

    /**
     * @param DocumentManager $manager
     */
    public function load(ObjectManager $manager)
    {
        if (!$manager instanceof DocumentManager) {
            $class = get_class($manager);
            throw new \RuntimeException("Fixture requires a PHPCR ODM DocumentManager instance, instance of '$class' given.");
        }

        $session = $manager->getPhpcrSession();

        $basepath = $this->container->getParameter('cmf_media.persistence.phpcr.media_basepath');
        NodeHelper::createPath($session, $basepath);

        $basepath = $this->container->getParameter('cmf_content.persistence.phpcr.content_basepath');
        NodeHelper::createPath($session, $basepath);

        $yaml = new Parser();
        $data = $yaml->parse(
            file_get_contents(
                __DIR__.'/../../Resources/data/page.yml'
            )
        );

        $parent = $manager->find(null, $basepath);
        foreach ($data['static'] as $overview) {
            $path = $basepath . '/' . $overview['name'];
            $page = $manager->find(null, $path);
            if (!$page) {
                $class = isset($overview['class']) ? $overview['class'] : 'AppBundle\\Document\\DemoSeoContent';
                /** @var $page DemoSeoContent */
                $page = new $class();
                $page->setName($overview['name']);
                $page->setParentDocument($parent);
                $manager->persist($page);
            }

            if (is_array($overview['title'])) {
                foreach ($overview['title'] as $locale => $title) {
                    $page->setTitle($title);
                    $page->setBody($overview['body'][$locale]);

                    $manager->bindTranslation($page, $locale);
                }
            } else {
                $page->setTitle($overview['title']);
                $page->setBody($overview['body']);
            }


            if (isset($overview['publishable']) && $overview['publishable'] === false) {
                $page->setPublishable(false);
            }

            if (!empty($overview['publishStartDate'])) {
                $page->setPublishStartDate(new \DateTime($overview['publishStartDate']));
            }

            if (!empty($overview['publishEndDate'])) {
                $page->setPublishEndDate(new \DateTime($overview['publishEndDate']));
            }

            if (isset($overview['blocks'])) {
                foreach ($overview['blocks'] as $name => $block) {
                    $this->loadBlock($manager, $page, $name, $block);
                }
            }
        }

        //add a loading for a simple seo aware page
        $seoDemo = new DemoSeoContent();
        $seoDemo->setName('simple-seo-example');
        $seoDemo->setTitle('Simple seo example');
        $seoDemo->setBody(
            '<p>
                When implementing the SeoAwareInterface,
                you get a chance to configure SEO data
                (title, description, keywords and original url)
                of the document.
                The SonataAdminBundle integration allows admins to update this data.
            </p>'
        );
        $seoDemo->setParentDocument($parent);

        $seoMetadata = new SeoMetadata();
        $seoMetadata->setTitle('Simple seo example');
        $seoMetadata->setOriginalUrl('/home');
        $seoMetadata->setMetaDescription('This is a simple example of an seo aware document in the sandbox.');
        $seoMetadata->setMetaKeywords('Seo');
        $seoDemo->setSeoMetadata($seoMetadata);

        $manager->persist($seoDemo);
        $manager->bindTranslation($seoDemo, 'en');

        //add a loading for a simple seo aware page
        $seoDemo = new DemoSeoContent();
        $seoDemo->setName('simple-seo-property');
        $seoDemo->setTitle('Simple seo property');
        $seoDemo->setBody(
            '<p>
                SeoMetadata have got the possibility to
                to add custom properties for custom
                meta tags. You just to set a type, key
                and value
            </p>'
        );
        $seoDemo->setParentDocument($parent);

        $seoMetadata = new SeoMetadata();
        $seoMetadata->setTitle('Simple seo property');
        $seoMetadata->setMetaKeywords('Seo, Properties');
        $seoMetadata->addExtraHttp('content-type','text/html');
        $seoMetadata->addExtraName('robots','index, follow');
        $seoMetadata->addExtraProperty('og:title','extra title');
        $seoDemo->setSeoMetadata($seoMetadata);
        $manager->persist($seoDemo);

        $manager->bindTranslation($seoDemo, 'en');

        $manager->flush(); //to get ref id populated
    }

    /**
     * Load a block from the fixtures and create / update the node. Recurse if there are children.
     *
     * @param ObjectManager $manager    the document manager
     * @param string        $parentPath the parent of the block
     * @param string        $name       the name of the block
     * @param array         $block      the block definition
     */
    private function loadBlock(ObjectManager $manager, $parent, $name, $block)
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

        if ($className == 'Symfony\Cmf\Bundle\BlockBundle\Doctrine\Phpcr\ReferenceBlock') {
            $referencedBlock = $manager->find(null, $block['referencedBlock']);
            if (null === $referencedBlock) {
                throw new \Exception('did not find '.$block['referencedBlock']);
            }
            $document->setReferencedBlock($referencedBlock);
        } elseif ($className == 'Symfony\Cmf\Bundle\BlockBundle\Doctrine\Phpcr\ActionBlock') {
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
