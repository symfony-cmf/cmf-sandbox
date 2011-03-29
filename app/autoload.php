<?php

use Symfony\Component\ClassLoader\UniversalClassLoader;

$loader = new UniversalClassLoader();
$loader->registerNamespaces(array(
    'Symfony\\Cmf'                   => __DIR__.'/../src',
    'Symfony'                        => array(__DIR__.'/../vendor/symfony/src', __DIR__.'/../vendor/bundles'),
    'Sandbox'                        => __DIR__.'/../src',
    'Liip\\FunctionalTestBundle'     => __DIR__.'/../vendor/bundles',
    'Doctrine\\Common'               => __DIR__.'/../vendor/doctrine/common/lib',
    'Doctrine\\ODM\\PHPCR'           => __DIR__.'/../vendor/doctrine-phpcr-odm/lib',
    'Zend\\Log'                      => __DIR__.'/../vendor/zend-log',
    'Jackalope'                      => __DIR__.'/../vendor/doctrine-phpcr-odm/lib/vendor/jackalope/src',
    'PHPCR'                          => __DIR__.'/../vendor/doctrine-phpcr-odm/lib/vendor/jackalope/lib/phpcr/src',
));
$loader->registerPrefixes(array(
    'Twig_Extensions_' => __DIR__.'/../vendor/twig-extensions/lib',
    'Twig_'            => __DIR__.'/../vendor/twig/lib',
));
$loader->register();
