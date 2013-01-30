<?php

namespace Sandbox\BannerBundle\Block;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\BlockBundle\Block\BaseBlockService;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\BlockBundle\Model\BlockInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MediaBlockService extends BaseBlockService
{
    /**
     * @var string
     */
    private $template = 'SandboxBannerBundle:Block:block_image.html.twig';

    /**
     * {@inheritdoc}
     */
    public function buildEditForm(FormMapper $form, BlockInterface $block)
    {
        throw new \RuntimeException('Not used at the moment, editing using a frontend or backend UI could be changed here');
    }

    /**
     * {@inheritdoc}
     */
    public function validateBlock(ErrorElement $errorElement, BlockInterface $block)
    {
        throw new \RuntimeException('Not used at the moment, validation for editing using a frontend or backend UI could be changed here');
    }

    /**
     * {@inheritdoc}
     */
    function execute(BlockContextInterface $blockContext, Response $response = null)
    {
        if (!$response) {
            $response = new Response();
        }

        $block = $blockContext->getBlock();

        if ($block->getEnabled()) {
            return $this->renderResponse($blockContext->getTemplate(), array(
                'block'     => $block,
                'items'     => $block->getItems()->slice(0, $blockContext->getSetting('maxItems')),
                'settings'  => $blockContext->getSettings(),
            ), $response);
        }

        return $response;
    }

    /**
     * @param string $template
     */
    public function setTemplate($template)
    {
        $this->template = $template;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultSettings(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'template' => $this->template,
            'maxItems' => 4
        ));
    }
}
