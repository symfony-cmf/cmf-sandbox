<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2017 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Controller;

use Symfony\Cmf\Bundle\ContentBundle\Controller\ContentController as BaseContentController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Special routes to demo the features of the Doctrine Router in the CmfRoutingBundle.
 */
class ContentController extends BaseContentController
{
    /**
     * Action that is mapped in the controller_by_type map.
     *
     * This can inject something else for the template for content with this type
     *
     * @param object $contentDocument
     *
     * @return Response
     */
    public function typeAction($contentDocument)
    {
        if (!$contentDocument) {
            throw new NotFoundHttpException('Content not found');
        }

        $params = [
            'cmfMainContent' => $contentDocument,
            'info' => 'This page is rendered by <code>'.__METHOD__.'</code>. This controller was configured for this route type.',
        ];

        return $this->renderResponse('demo/controller.html.twig', $params);
    }

    /**
     * Action that is mapped in the controller_by_class map.
     *
     * This can inject something else for the template for this type of content.
     *
     * @param object $contentDocument
     *
     * @return Response the response
     */
    public function classAction($contentDocument)
    {
        if (!$contentDocument) {
            throw new NotFoundHttpException('Content not found');
        }

        $params = [
            'cmfMainContent' => $contentDocument,
            'info' => 'This page is rendered by <code>'.__METHOD__.'</code>. This controller will be called for content objects that are instances of <code>AppBundle\Document\DemoClassContent</code>.',
        ];

        return $this->renderResponse('demo/controller.html.twig', $params);
    }

    /**
     * Action that is explicitly referenced in the _controller field of a content.
     *
     * This can inject something else for the template
     *
     * @param object $contentDocument
     *
     * @return Response
     */
    public function specialAction($contentDocument)
    {
        if (!$contentDocument) {
            throw new NotFoundHttpException('Content not found');
        }

        $params = [
            'cmfMainContent' => $contentDocument,
            'info' => 'This page is rendered by <code>'.__METHOD__.'</code>. This controller was explicitely defined for the route by setting the <code>_controller</code> default.',
        ];

        return $this->renderResponse('demo/controller.html.twig', $params);
    }
}
