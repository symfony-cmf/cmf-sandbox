<?php

namespace Sandbox\AdminBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;

class MultilangMenuItemAdmin extends Admin
{
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id', 'text')
            ->add('locale', 'text') // TODO: this should rather list all defined locales
            ->add('name', 'text')
            ->add('label', 'text')
            ->add('uri', 'text')
            ->add('route', 'text')
        ;
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('General')
                ->add('id', 'text')
                ->add('locale', 'text')
                ->add('name', 'text')
                ->add('label', 'text')
                ->add('uri', 'text', array('required' => false))
                ->add('route', 'text', array('required' => false))
                ->add('content', 'doctrine_phpcr_type_tree_model', array('class' => 'Sandbox\MainBundle\Document\EditableStaticContent', 'required' => false))
            ->end();
    }

    public function getExportFormats()
    {
        return array();
    }
}
