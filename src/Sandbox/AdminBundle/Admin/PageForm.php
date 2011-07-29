<?php

namespace Sandbox\AdminBundle\Admin;

use Symfony\Component\Form\Form;
use Symfony\Component\Form\TextField;
use Symfony\Component\Form\TextareaField;
use Symfony\Component\Form\EmailField;
use Symfony\Component\Form\CheckboxField;
use Symfony\Component\Form\ChoiceField;
use Symfony\Component\Form\LanguageField;

class PageForm extends Form
{

    /**
     * @param string $name
     * @param array $options
     */
    public function __construct($name = null, array $options = array())
    {
        $this->addOption('dm');

        parent::__construct($name, $options);
    }

    protected function configure()
    {
        $dm = $this->getOption('dm');
        $query = $dm->getPhpcrSession()->getWorkspace()->getQueryManager()->createQuery('SELECT * FROM [nt:unstructured]', 'JCR-SQL2');
        $pages = $query->execute();

        $parents = array();
        foreach ($pages as $key => $row) {
            $parents[$row->getPath()] = $row->getPath();
        }

        $this->add(new TextField('title', array('max_length' => 100)));
        $this->add(new ChoiceField('parent', array('property_path' => null, 'choices' => $parents)));
        $this->add(new TextField('name', array('max_length' => 100)));
        $this->add(new TextareaField('content'));
    }
}
