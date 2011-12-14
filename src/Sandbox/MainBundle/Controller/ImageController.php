<?php

namespace Sandbox\MainBundle\Controller;

use Sandbox\MainBundle\Document\Image;

use Doctrine\ODM\PHPCR\DocumentManager;

use FOS\RestBundle\View\ViewHandlerInterface;
use FOS\RestBundle\View\View;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ImageController extends Controller
{
    protected $dm;

    /**
     * @var FOS\RestBundle\View\ViewHandlerInterface
     */
    private $viewHandler;

    protected $images_mime = array(
            'image/png',
            'image/jpeg',
            'image/gif',
            'image/bmp',
            'image/vnd.microsoft.icon',
            'image/tiff',
            'image/svg+xml');

    public function __construct(ContainerInterface $container, DocumentManager $dm, ViewHandlerInterface $viewHandler)
    {
        $this->container = $container;
        $this->dm = $dm;
        $this->viewHandler = $viewHandler;
    }

    public function uploadAction(Request $request)
    {
        $basepath = $this->container->getParameter('symfony_cmf_content.static_basepath');
        $error = '';

        $files = $request->files;

        foreach ($files->all() as $file ) {
            if (in_array($file->getClientMimeType(), $this->images_mime)) {
                $name = $file->getClientOriginalName();
                $jcrPath = $basepath.'/'.md5($name);
                if (!$this->dm->find(null, $jcrPath)) {
                    $image = new Image();
                    $image->setPath($jcrPath);
                    $image->setContent(file_get_contents($file->getPathname()));
                } else {
                    $error = 'This file already exists in your backend.';
                }

                $this->dm->persist($image);
            } else {
                $error = "What you're trying to upload is not an image.";
            }
        }

        $this->dm->flush();

        $data = array("path" => $file->getPathname(), "error" => $error);

        $view = View::create($data);
        return $this->viewHandler->handle($view);
    }
}
