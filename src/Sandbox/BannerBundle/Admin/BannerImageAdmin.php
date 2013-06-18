<?php

namespace Sandbox\BannerBundle\Admin;

use Sonata\DoctrinePHPCRAdminBundle\Admin\Admin;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;

class BannerImageAdmin extends Admin
{
//    protected $baseRouteName = 'sandbox_banner_image_admin';
//    protected $baseRoutePattern = '/banner/banner_image';

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
//            ->add('name')
            ->add('title', 'text', array('attr' => array('class' => '')))
            ->add('description', 'textarea', array('attr' => array('class' => '')))
            ->add('url', 'text', array('attr' => array('class' => '')))
            ->add('image', 'sonata_type_model_list', array(), array('link_parameters' => array('context' => 'default')))
            ->add('position', 'hidden')
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name')
            ->add('title', 'text')
            ->add('updated_at')
        ;
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        //$collection->remove('create');
        $collection->remove('edit');
        $collection->remove('delete');
        $collection->remove('show');
    }

    public function getBatchActions()
    {
        return array();
    }

    public function getExportFormats()
    {
        return array();
    }
}
