<?php

namespace Uwe\PageBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class PageBundle extends Bundle
{
    public function getNamespace()
    {
        return __NAMESPACE__;
    }

    public function getPath()
    {
        return strtr(__DIR__, '\\', '/');
    }
}
