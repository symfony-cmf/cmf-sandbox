<?php


namespace AppBundle\Admin;

use Symfony\Cmf\Bundle\BlockBundle\Admin\Imagine\ImagineBlockAdmin;
use Sonata\AdminBundle\Form\FormMapper;

class BaseImagineBlockAdmin extends ImagineBlockAdmin {

    protected function configureFormFields(FormMapper $formMapper)
    {
        parent::configureFormFields($formMapper);
        $formMapper
            ->with('form.group_general')
            ->add(
                'parentDocument',
                'doctrine_phpcr_odm_tree',
                array('root_node' => $this->getRootPath(), 'choice_list' => array(), 'select_root_node' => true)
            )
            ->add('name', 'text')
            ->end();
    }
}