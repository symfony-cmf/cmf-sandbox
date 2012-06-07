<?php

namespace Sandbox\AdminBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;

class RouteAdmin extends Admin
{
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('path', 'text')
        ;
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('General')
                ->add('path', 'text')
                //TODO ->add('parent', 'text')
                //TODO ->add('name', 'text')
                ->add('variablePattern', 'text', array('required' => false))
                ->add('routeContent', 'doctrine_phpcr_type_tree_model', array('class' => 'Sandbox\MainBundle\Document\EditableStaticContent', 'required' => false, 'rootNode' => '/cms/content'))
                // TODO edit key-value fields for defaults and options
            ->end();
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name',  'doctrine_phpcr_string')
            ;
    }

    public function getExportFormats()
    {
        return array();
    }
}
