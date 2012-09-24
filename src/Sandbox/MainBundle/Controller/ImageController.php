<?php

namespace Sandbox\MainBundle\Controller;

use Sandbox\MainBundle\Document\Image;

use Doctrine\ODM\PHPCR\DocumentManager;

use FOS\RestBundle\View\ViewHandlerInterface;
use FOS\RestBundle\View\View;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;

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

    public function displayAction($id)
    {
        $basepath = $this->container->getParameter('symfony_cmf_content.static_basepath');

        $image = $this->dm->find(null, $basepath.'/'.$id);

        $data = stream_get_contents($image->content);

        $response = new Response($data);
        $response->headers->set('Content-Type', 'image/jpeg');
        $response->setPublic();

        $date = new \DateTime();
        $date->setTimestamp(time() + 332640000);
        $response->setExpires($date);
        $response->setMaxAge(332640000);

        return $response;
    }

    public function uploadAction(Request $request)
    {
        $basepath = $this->container->getParameter('symfony_cmf_content.static_basepath');

        $files = $request->files;
        $id = '';

        foreach ($files->all() as $file ) {
            if (! in_array($file->getClientMimeType(), $this->images_mime)) {
                throw new HttpException(400, "What you're trying to upload is not an image.");
            }

            // TODO: this code is ridiculous. either save images with md5 and no need to check if exists
            // or use filename.
            // ideally we would put the image under the entity that was edited. this would require that
            // the entity id is transmitted by the hallo dialog which i think it is not.

            // $name = $file->getClientOriginalName();
            $path = $file->getPathname();
            $id = md5(time());
            $jcrPath = $basepath.'/'.$id;
            if (!$this->dm->find(null, $jcrPath)) {
                $image = new Image();
                $image->setPath($jcrPath);
                $image->name = $id;
                $image->content = file_get_contents($path);
                $image->tags = explode(',', $request->get('tags'));
                $this->dm->persist($image);
                $this->dm->flush();
            }
            // otherwise we will still redirect to the existing document
        }

        return $this->redirect($this->generateUrl('hallo_image_display', array('id' => $id)));
    }
}
