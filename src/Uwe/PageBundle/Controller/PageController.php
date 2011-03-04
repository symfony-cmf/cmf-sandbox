<?php

namespace Uwe\PageBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Uwe\Documents\Page;

class PageController extends Controller
{
    public function createAction($path, $title)
    {
        $page = new Page();
        $page->setPath($path);
        $page->setTitle($title);
        $dm = $this->get('doctrine.phpcr_odm.document_manager');
        $dm->persist($page, $path);
        $dm->flush();
        return new Response('OK');
    }


    public function showAction($path)
    {
        $dm = $this->get('doctrine.phpcr_odm.document_manager');
        $page = $dm->find('Uwe\Documents\Page', $path);
        if ($page)
        {
die(var_dump($page));
            return new Response($page->getTitle());
        }
        else
        {
            throw new NotFoundHttpException();
        }
    }
}
