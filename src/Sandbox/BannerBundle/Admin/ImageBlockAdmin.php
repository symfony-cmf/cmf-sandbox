<?php

namespace Sandbox\BannerBundle\Admin;

use Sonata\DoctrinePHPCRAdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sandbox\BannerBundle\Document\ImageBlock;

class ImageBlockAdmin extends Admin
{
//    protected $baseRouteName = 'sandbox_banner_image_block_admin';
//    protected $baseRoutePattern = '/banner/image_block';

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('parentDocument', 'doctrine_phpcr_odm_tree', array('choice_list' => array(), 'root_node' => $this->root, 'select_root_node' => true))
            ->add('name')
            ->add('items', 'sonata_type_collection', array('by_reference' => false, 'required' => false), array(
                'edit' => 'inline',
                'inline' => 'table',
                'sortable'  => 'position',
                'admin_code' => 'sandbox_banner.admin.banner_image'
            ))
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->add('name')
            ->add('updatedAt', 'datetime')
        ;
    }

    public function getExportFormats()
    {
        return array();
    }

    public function getNewInstance()
    {
        /** @var $new ImageBlock */
        $new = parent::getNewInstance();
        if ($this->hasRequest()) {
            $parentId = $this->getRequest()->query->get('parent');
            if (null !== $parentId) {
                $new->setParentDocument($this->getModelManager()->find(null, $parentId));
            }
        }
        return $new;
    }

    /**
     * {@inheritdoc}
     */
    public function prePersist($object)
    {
        // fix bug with annotations - PrePersist not being called
        $object->reorderItems();
    }

    /**
     * {@inheritdoc}
     */
    public function preUpdate($object)
    {
        // fix bug with annotations - PreUpdate not being called
        $object->reorderItems();
    }
}
