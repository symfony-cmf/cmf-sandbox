<?php

namespace Sandbox\AdminBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;

class ContentAdmin extends Admin
{
    /**
     * Root path for the route content selection
     * @var string
     */
    protected $contentRoot;

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('path', 'text')
            ->add('title')
            ->add('name')
            ->add('content')
        ;
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('General')
                ->add('parent', 'doctrine_phpcr_type_tree_model', array('root_node' => $this->contentRoot, 'choice_list' => array(), 'select_root_node' => true))
                ->add('path', 'text')
                ->add('title', 'text')
                ->add('name', 'text')
                ->add('content', 'textarea')
            ->end();
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('title', 'doctrine_phpcr_string')
            ->add('name',  'doctrine_phpcr_string')
            ;
    }

    public function getNewInstance()
    {
        /** @var $new MenuItem */
        $new = parent::getNewInstance();
        if ($this->hasRequest()) {
            $parentId = $this->getRequest()->query->get('parent');
            if (null !== $parentId) {
                $new->setParent($this->getModelManager()->find(null, $parentId));
            }
        }
        return $new;
    }

    public function setContentRoot($contentRoot)
    {
        $this->contentRoot = $contentRoot;
    }

    public function getExportFormats()
    {
        return array();
    }

}
