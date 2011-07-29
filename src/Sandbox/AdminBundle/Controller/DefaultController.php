<?php

namespace Sandbox\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\DataError;
use Symfony\Component\Form\PropertyPath;

use Sandbox\AdminBundle\Document\Page;
use Sandbox\AdminBundle\Admin\PageForm;

use Jackalope\Transport\Davex\HTTPErrorException; //ugh, this should be something in phpcr, not jackalope specific

class DefaultController extends Controller
{
    /**
     * @var \Doctrine\ODM\PHPCR\DocumentManager
     */
    protected $dm = null;

    protected $form;

    protected $request;

    public function __construct($container, $dm, $form, $request)
    {
        $this->setContainer($container);
        $this->dm = $dm;
        $this->form = $form;
        $this->request = $request;
    }

    public function indexAction()
    {
        return $this->render('SandboxAdminBundle:Admin:index.html.twig');
    }

    public function createAction()
    {
        // bind form to page model
        $page = new Page();
        $this->form->bind($this->request, $page);

        if ($this->form->isValid()) {

            try {

                // path for page
                $parent = $this->form->get('parent')->getData();
                $path = $parent . '/' . $page->name;

                // save page
                $this->dm->persist($page, $path);
                $this->dm->flush();

                // redirect with message
                $this->request->getSession()->setFlash('notice', 'Page created!');
                return $this->redirect($this->generateUrl('admin'));

            } catch (HTTPErrorException $e) {

                $path = new PropertyPath('name');
                $this->form->addError(new DataError('Name already in use.'), $path->getIterator());

            }
        }

        return $this->render('SandboxAdminBundle:Admin:create.html.twig', array('form' => $this->form));
    }
}
