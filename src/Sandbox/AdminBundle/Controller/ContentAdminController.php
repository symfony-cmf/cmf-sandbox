<?php

namespace Sandbox\AdminBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Symfony\Component\HttpFoundation\Response;

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

    /**
     * TODO: this is a temp hack in the Sandbox. This should go into the 
     * base class in the SAB CRUD controller we are extending here.
     *
     * return the Response object associated to the create action
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createAction()
    {
        if (false === $this->admin->isGranted('CREATE')) {
            throw new AccessDeniedException();
        }

        $id = $this->get('request')->get($this->admin->getIdParameter());

        $id= urldecode($id);
        $object = $this->admin->getNewInstance();
        $idField = $this->admin->getModelManager()->getModelIdentifier($this->admin->getClass());
        $object->{'set'.$idField}($id);

        $this->admin->setSubject($object);

        $form = $this->admin->getForm();
        $form->setData($object);

        if ($this->get('request')->getMethod() == 'POST') {
            $form->bindRequest($this->get('request'));

            if ($form->isValid()) {
                $this->admin->create($object);

                if ($this->isXmlHttpRequest()) {
                    return $this->renderJson(array(
                        'result' => 'ok',
                        'objectId' => $this->admin->getNormalizedIdentifier($object)
                    ));
                }

                $this->get('session')->setFlash('sonata_flash_success','flash_create_success');
                // redirect to edit mode
                return $this->redirectTo($object);
            }
            $this->get('session')->setFlash('sonata_flash_error', 'flash_create_error');
        }

        $view = $form->createView();

        // set the theme for the current Admin Form
        $this->get('twig')->getExtension('form')->setTheme($view, $this->admin->getFormTheme());

        return $this->render($this->admin->getEditTemplate(), array(
            'action' => 'create',
            'form'   => $view,
            'object' => $object,
        ));
    }

    public function treeAction()
    {
         $pool = $this->get('sonata.admin.pool');
         $classes = $pool->getAdminClasses();
         $adminClasses = array();
         foreach ($classes as $class) {
             $instance = $this->get($class);
             $routeCollection = array();
             foreach ($instance->getRoutes()->getElements() as $route) {
                 array_push($routeCollection, $route->getPattern());
             }
             array_push($adminClasses, array(
                 'label' => $instance->getLabel(),
                 'route' => $routeCollection));
         }
         return $this->renderJson($adminClasses);
    }

}
