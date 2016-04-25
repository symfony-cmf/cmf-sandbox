<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Cmf\Bundle\ContentBundle\Admin\StaticContentAdmin;

class BaseStaticContentAdmin extends StaticContentAdmin{

    protected $embeddedAdminCode;


    /**
     * Configure the service name (admin_code) of the admin service for the embedded slides
     *
     * @param string $adminCode
     */
    public function setEmbeddedContainerAdmin($adminCode)
    {
        $this->embeddedAdminCode = $adminCode;
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        parent::configureFormFields($formMapper);
        $formMapper
            ->with('Items')
            ->add('children', 'sonata_type_collection',
                ['label' => 'Text'],
                [
                    'edit' => 'inline',
                    'inline' => 'standard',
                    'admin_code' => $this->embeddedAdminCode,
                ])
            ->end();
    }
}