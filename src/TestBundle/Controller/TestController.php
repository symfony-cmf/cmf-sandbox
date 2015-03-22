<?php

namespace TestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TestController extends Controller
{
    public function indexAction()
    {
        return $this->render('TestBundle:Test:index.html.twig', array('title'=>'Normal Symfony Route'));
    }

}
