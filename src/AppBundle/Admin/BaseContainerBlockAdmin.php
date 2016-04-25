<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Cmf\Bundle\BlockBundle\Admin\ContainerBlockAdmin;

class BaseContainerBlockAdmin extends ContainerBlockAdmin {
    /**
     * Service name of the sonata_type_collection service to embed
     *
     * @var string
     */
    protected $embeddedAdminCode;

    /**
     * Configure the service name (admin_code) of the admin service for the embedded slides
     *
     * @param string $adminCode
     */
    public function setEmbeddedMediaAdmin($adminCode)
    {
        $this->embeddedAdminCode = $adminCode;
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('form.group_general')
                ->add('parentDocument', 'doctrine_phpcr_odm_tree', array('root_node' => $this->getRootPath(), 'choice_list' => array(), 'select_root_node' => true))
                ->add('name', 'text')
                ->end()
            ->with('Items')
                ->add('children', 'sonata_type_collection',
                    ['label' => 'Image(s)'],
                    [
                        'edit' => 'inline',
                        'inline' => 'table',
                        'admin_code' => $this->embeddedAdminCode,
                        'sortable' => 'position',
                    ])
                ->end();
    }
}