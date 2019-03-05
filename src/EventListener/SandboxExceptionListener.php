<?php

declare(strict_types=1);

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\EventListener;

use Doctrine\ODM\PHPCR\DocumentManager;
use Doctrine\ODM\PHPCR\DocumentManagerInterface;
use PHPCR\RepositoryException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Exception listener that will handle not found exceptions and try to give the
 * first time installer some clues what is wrong.
 */
class SandboxExceptionListener implements EventSubscriberInterface
{
    /**
     * @var DocumentManager
     */
    private $documentManager;
    /**
     * @var string
     */
    private $menuBasePath;

    /**
     * SandboxExceptionListener constructor.
     *
     * @param string $menuBasePath
     */
    public function __construct(string $menuBasePath)
    {
        $this->menuBasePath = $menuBasePath;
    }

    public function setDocumentManager(DocumentManagerInterface $documentManager): void
    {
        $this->documentManager = $documentManager;
    }

    public function onKernelException(GetResponseForExceptionEvent $event): void
    {
        if (!$event->getException() instanceof NotFoundHttpException) {
            return;
        }

        if (null !== $this->documentManager) {
            $error = 'Missing the service doctrine_phpcr.odm.default_document_manager.';
        } else {
            try {
                $doc = $this->documentManager->find(null, $this->menuBasePath);
                if ($doc) {
                    $error = sprintf('Hm. No clue what goes wrong. Maybe this is a real 404?<pre>%s</pre>', $event->getException());
                } else {
                    $error = 'Did you load the fixtures? See README for how to load them. I found no node at menu_basepath: '.$this->menuBasePath;
                }
            } catch (RepositoryException $e) {
                $error = sprintf(
                    'There was an exception loading the document manager:
                            <strong>%s</strong>
                            <br/>\n
                            <em>
                                Make sure you have a phpcr backend properly set up and running.
                            </em>
                            <br/>
                            <pre>%s</pre>',
                    $e->getMessage(),
                    $e->__toString()
                    );
            }
        }
        // do not even trust the templating system to work
        $response = new Response("<html><body>
            <h2>Sandbox</h2>
            <p>If you see this page, it means your sandbox is not correctly set up.
               Please see the README file in the sandbox root folder and if you can't figure out
               what is wrong, ask us on freenode irc #symfony-cmf or the mailinglist cmf-users@groups.google.com.
            </p>

            <p>If you are seeing this page as the result of an edit in the admin tool, please report what you were doing
                to our <a href=\"https://github.com/symfony-cmf/cmf-sandbox/issues/new\">ticket system</a>,
                so that we can add means to prevent this issue in the future. But to get things working again
                for now, please just <a href=\"".$event->getRequest()->getSchemeAndHttpHost()."/reload-fixtures.php\">click here</a>
                to reload the data fixtures.
            </p><p style='color:red;'>
               <strong>Detected the following problem</strong>: $error
            </p>
            </body></html>
            ");

        $event->setResponse($response);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => ['onKernelException', 0],
        ];
    }
}
