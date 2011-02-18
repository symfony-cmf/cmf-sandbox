<?php

namespace Bundle\Symfony\CMFCoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('CMFCoreBundle:Default:index.html.twig');
    }
}
