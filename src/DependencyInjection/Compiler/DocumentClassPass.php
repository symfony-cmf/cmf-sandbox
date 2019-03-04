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

namespace App\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * We need to use an other document class for the StaticContentAdmin.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@mayflower.de>
 */
class DocumentClassPass implements CompilerPassInterface
{
    public const DOCUMENT_CLASS = 'App\Document\DemoSeoContent';

    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $adminServiceDefinition = $container->getDefinition('cmf_sonata_phpcr_admin_integration.content.admin');
        $adminServiceDefinition->replaceArgument(1, self::DOCUMENT_CLASS);
    }
}
