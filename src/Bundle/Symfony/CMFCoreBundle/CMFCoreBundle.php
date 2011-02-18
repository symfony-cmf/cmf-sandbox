<?php

namespace Bundle\Symfony\CMFCoreBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class CMFCoreBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function getNamespace()
    {
        return __NAMESPACE__;
    }

    /**
     * {@inheritdoc}
     */
    public function getPath()
    {
        return strtr(__DIR__, '\\', '/');
    }
}
