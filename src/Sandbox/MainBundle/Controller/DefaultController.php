<?php

namespace Sandbox\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('SandboxMainBundle:Default:index.html.twig');
    }
}
