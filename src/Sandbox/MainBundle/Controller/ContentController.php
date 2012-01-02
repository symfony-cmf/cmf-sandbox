<?php

namespace Sandbox\MainBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Special routes to demo the features of the Doctrine Router in the ChainRoutingBundle
 */
class ContentController extends Controller
{
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Action that is mapped in the controller_by_alias map
     *
     * This can inject something else for the template for content with this alias
     *
     * @param object $contentDocument
     *
     * @return \Symfony\Component\HttpFoundation\Response the response
     */
    public function aliasAction($contentDocument)
    {
        if (!$contentDocument) {
            throw new NotFoundHttpException('Content not found');
        }
        $params = array(
            'title' => $contentDocument->title,
            'page' => $contentDocument,
            'example' => 'Additional value injected by the controller for this alias (this could work without content if we want)',
        );

        return $this->render('SandboxMainBundle:Demo:controller.html.twig', $params);
    }

    /**
     * Action that is mapped in the controller_by_class map
     *
     * This can inject something else for the template for this type of content.
     *
     * @param object $contentDocument
     *
     * @return \Symfony\Component\HttpFoundation\Response the response
     */
    public function classAction($contentDocument)
    {
        if (!$contentDocument) {
            throw new NotFoundHttpException('Content not found');
        }
        $params = array(
            'title' => $contentDocument->title,
            'page' => $contentDocument,
            'example' => 'Additional value injected by the controller for all content mapped to classAction',
        );

        return $this->render('SandboxMainBundle:Demo:controller.html.twig', $params);
    }

    /**
     * Action that is explicitly referenced in the _controller field of a content
     *
     * This can inject something else for the template
     *
     * @param object $contentDocument
     *
     * @return \Symfony\Component\HttpFoundation\Response the response
     */
    public function specialAction($contentDocument)
    {
        if (!$contentDocument) {
            throw new NotFoundHttpException('Content not found');
        }
        $params = array(
            'title' => $contentDocument->title,
            'page' => $contentDocument,
            'example' => 'Additional value injected by the controller when explicitly referenced',
        );

        return $this->render('SandboxMainBundle:Demo:controller.html.twig', $params);
    }
}
