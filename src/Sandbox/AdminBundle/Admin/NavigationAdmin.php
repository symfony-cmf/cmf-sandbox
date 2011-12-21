<?php

namespace Sandbox\AdminBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;

use Knp\Menu\ItemInterface as MenuItemInterface;

class NavigationAdmin extends Admin
{
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('path', 'text')
            ->add('lang', 'text')
            ->add('label', 'text')
            ->add('info', 'text')
            ->add('visible')
            ->add('redirect_to_navigation')
//            ->add('reference', 'node')
            ->add('controller')
        ;
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('General')
                ->add('path', 'text')
                ->add('lang', 'text')
                ->add('label', 'text')
                ->add('info', 'text')
                ->add('visible')
            ->end();
    }
    protected function configureShowField(ShowMapper $showMapper)
    {
        $showMapper
                ->add('path', 'text')
                ->add('lang', 'text')
                ->add('label', 'text')
                ->add('info', 'text')
                ->add('visible')
            ;
    }

    public function getListTemplate()
    {
        return 'SandboxAdminBundle:CRUD:list.html.twig';
    }

    public function getEditTemplate()
    {
        return 'SandboxAdminBundle:CRUD:edit.html.twig';
    }
}
