<?php

use Symfony\Component\ClassLoader\UniversalClassLoader;

$loader = new UniversalClassLoader();
$loader->registerNamespaces(array(
    'Symfony\\CMF'                   => __DIR__.'/../src',
    'Symfony'                        => __DIR__.'/../vendor/symfony/src',
    'Sandbox'                        => __DIR__.'/../src',
    'Liip'                           => __DIR__.'/../src',
    'Doctrine\\Common'               => __DIR__.'/../vendor/doctrine/common/lib',
    'Doctrine\\ODM\\PHPCR'           => __DIR__.'/../vendor/doctrine-phpcr/lib',
    'Zend\\Log'                      => __DIR__.'/../vendor/zend-log',
    'Jackalope'                      => __DIR__.'/../vendor/doctrine-phpcr/lib/vendor/jackalope/src',
    'PHPCR'                          => __DIR__.'/../vendor/doctrine-phpcr/lib/vendor/jackalope/lib/phpcr/src',
));
$loader->registerPrefixes(array(
    'Twig_'            => __DIR__.'/../vendor/twig/lib',
));
$loader->register();
