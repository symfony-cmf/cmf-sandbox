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

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sonata\BlockBundle\Model\BlockInterface;

class DefaultController extends Controller
{
    /**
     * Action that is referenced in an ActionBlock.
     *
     * @param BlockInterface $block
     *
     * @return Response
     */
    public function blockAction($block)
    {
        return $this->render('block/demo_action_block.html.twig', array(
            'block' => $block,
        ));
    }

    /**
     * @Route("/hello", name="symfony_route")
     */
    public function helloAction()
    {
        return $this->render('static_content/hello.html.twig');
    }
}
