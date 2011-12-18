<?php

namespace Sandbox\AdminBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController as Controller;

class ContentAdminController extends Controller
{
    protected $content_path = '';

    /**
     * @param $content_path phpcr path to the content root node
     */
    public function __construct($content_path)
    {
        $this->content_path = $content_path;
    }

    public function render($template, array $params = array(), \Symfony\Component\HttpFoundation\Response $response = null)
    {
        $params['treeroot'] = $this->content_path;
        return parent::render($template, $params, $response);
    }
}
