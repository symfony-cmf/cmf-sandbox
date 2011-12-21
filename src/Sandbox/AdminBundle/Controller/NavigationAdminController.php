<?php

namespace Sandbox\AdminBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController as Controller;

class NavigationAdminController extends Controller
{
    protected $navigation_path = '';

    /**
     * @param $navigation_path phpcr path to the content root node
     */
    public function __construct($navigation_path)
    {
        $this->navigation_path = $navigation_path;
    }

    public function render($template, array $params = array(), \Symfony\Component\HttpFoundation\Response $response = null)
    {
        $params['treeroot'] = $this->navigation_path;
        return parent::render($template, $params, $response);
    }
}
