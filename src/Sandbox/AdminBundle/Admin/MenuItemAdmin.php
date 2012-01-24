<?php

namespace Sandbox\AdminBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;

use Knp\Menu\ItemInterface as MenuItemInterface;

class MenuItemAdmin extends Admin
{
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('path', 'text')
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
                ->add('path', 'text')
                ->add('name', 'text')
                ->add('label', 'text')
                ->add('uri', 'text')
                ->add('route', 'text')
            ->end();
    }
    protected function configureShowField(ShowMapper $showMapper)
    {
        $showMapper
                ->add('path', 'text')
                ->add('name', 'text')
                ->add('label', 'text')
                ->add('uri', 'text')
                ->add('route', 'text')
            ;
    }
}
