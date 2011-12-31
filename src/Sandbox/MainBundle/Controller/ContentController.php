<?php

namespace Sandbox\MainBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * replacement for Symfony\Cmf\Bundle\ContentBundle\Controller\ContentController
 * to use a different template.
 * TODO: make ContentController more flexible and this class obsolete
 */
class ContentController extends Controller
{
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->defaultTemplate = 'SandboxMainBundle:EditableStaticContent:index.html.twig';
    }

    /**
     * @param StaticContent $contentDocument
     * @param string $path the url path for the current navigation item
     * @param string $template the template name to be used with this content
     * @param array $translationUrls urls to all language versions to pass on to twig
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function indexAction($contentDocument, $path, $template = null, $translationUrls = array())
    {
        if (!$contentDocument) {
            throw new NotFoundHttpException('Content not found: ' . $path);
        }
        if ($template === null) {
            $template = $this->defaultTemplate;
        }

        $params = array(
            'title' => $contentDocument->title,
            'page' => $contentDocument,
            'url' => $path,
//            'translationUrls' => $translationUrls,
        );

        return $this->render($template, $params);
    }

    /**
     * Render the home page
     *
     * This could inject something else for the template
     *
     * @param object $contentDocument
     */
    public function homepageAction($contentDocument)
    {
        if (!$contentDocument) {
            throw new NotFoundHttpException('Content not found');
        }
        $params = array(
            'title' => $contentDocument->title,
            'page' => $contentDocument,
        );

        return $this->render('SandboxMainBundle:Homepage:index.html.twig', $params);
    }
}
