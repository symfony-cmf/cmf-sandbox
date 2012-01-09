<?php

namespace Sandbox\MainBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * TODO this should be moved to an exception controller
 */
class SandboxController extends Controller
{
    public function indexAction()
    {
        if (! $this->container->has('doctrine_phpcr.odm.default_document_manager')) {
            $error = 'Missing the service doctrine_phpcr.odm.default_document_manager.';
        } else {
            try {
                $om = $this->container->get('doctrine_phpcr.odm.default_document_manager');
                $doc = $om->find(null, $this->container->getParameter('symfony_cmf_menu.menu_basepath'));
                if ($doc) {
                    $error = 'Hm. No clue what goes wrong.';
                } else {
                    $error = 'Did you load the fixtures? I found no node at menu_basepath';
                }
            } catch(\PHPCR\RepositoryException $e) {
                $error = 'There was an exception loading the document manager: ' . $e->getMessage() .
                    "<br/>\n<em>Make sure you have a phpcr backend properly set up and running.</em>";
            }
        }
        return new Response("<h2>Sandbox</h2>
            <p>If you see this page, it means your sandbox is not correctly set up.
               Please see the README file in the sandbox root folder and if you can't figure out
               what is wrong, ask us on freenode irc #symfony-cmf or the mailinglist symfony-cmf-users@groups.google.com.
            </p><p style='color:red;'>
               <strong>Detected the following problem</strong>: $error
            </p>
            ");
    }
}
