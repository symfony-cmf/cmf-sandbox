<?php

namespace Sandbox\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Symfony\Cmf\Bundle\CoreBundle\Helper\PathMapperInterface;

class DefaultController extends Controller
{
    /**
     * @var \Bundle\DoctrinePHPCRBundle\JackalopeLoader
     */
    protected $session = null;

    protected $mapper;
    /**
     * @var \Doctrine\ODM\PHPCR\DocumentManager
     */
    protected $dm = null;

    /**
     * this is a bit hacky: we cache the page but if this service is used for more than one node, this messes up major
     * however, the repository has a bug that prevents the root not from being instantiated more than once
     */
    protected $page;

    public function __construct($container, $phpcr, PathMapperInterface $mapper, $dm)
    {
        $this->setContainer($container);
        $this->session = $phpcr->getSession();
        $this->mapper = $mapper;
        $this->dm = $dm;
    }

    public function indexAction($path)
    {
        $path = $this->mapper->getStorageId($path);
        if ($this->session->itemExists($path) === false) {
            throw new NotFoundHttpException("Page not found '$path'");
        }

        $this->page = $this->dm->getRepository('Sandbox\MainBundle\Document\Page')->find($path);

        return $this->render('SandboxMainBundle:Default:index.html.twig', array('url'=>$path, 'routename' => 'cms'));
    }

    /**
     * render the document identified by url
     */
    public function contentAction($url)
    {
        //$node = $this->dm->getRepository('Liip\HackdayBundle\Document\Page')->find($url);
        return $this->render('SandboxMainBundle:Default:document.html.twig', array('url' => $url, 'node'=>$this->page));
    }
}
