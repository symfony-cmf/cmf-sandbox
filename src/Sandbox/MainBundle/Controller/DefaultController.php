<?php

namespace Sandbox\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sonata\BlockBundle\Model\BlockInterface;

class DefaultController extends Controller
{
    /**
     * Action that is referenced in an ActionBlock
     *
     * @param BlockInterface $block
     *
     * @return Response
     */
    public function blockAction($block)
    {
        return $this->render('SandboxMainBundle:Block:demo_action_block.html.twig', array(
            'block' => $block
        ));
    }
}
