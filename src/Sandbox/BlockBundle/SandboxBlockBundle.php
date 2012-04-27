<?php

namespace Sandbox\BlockBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class SandboxBlockBundle extends Bundle
{
    public function getParent()
    {
        return 'SymfonyCmfBlockBundle';
    }
}
