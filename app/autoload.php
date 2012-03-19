<?php

require __DIR__ . '/../vendor/symfony/src/Symfony/Component/ClassLoader/UniversalClassLoader.php';

use Symfony\Component\ClassLoader\UniversalClassLoader;
use Doctrine\Common\Annotations\AnnotationRegistry;

$loader = new UniversalClassLoader();
$loader->registerNamespaces(array(
    // cmf stuff
    'Symfony\\Cmf'                          => array(__DIR__.'/../vendor/symfony-cmf/src', __DIR__.'/../vendor/bundles'),
    'Doctrine\\Bundle'                      => __DIR__.'/../vendor/bundles',
    'Doctrine\\ODM\\PHPCR'                  => __DIR__.'/../vendor/doctrine-phpcr-odm/lib',
    'Doctrine\\Common'                      => __DIR__.'/../vendor/doctrine-phpcr-odm/lib/vendor/doctrine-common/lib',
    'Doctrine\\DBAL'                        => __DIR__.'/../vendor/doctrine-dbal/lib',
    'Doctrine\\ORM'                         => __DIR__.'/../vendor/doctrine/lib',
    'Doctrine\\Common'                      => __DIR__.'/../vendor/doctrine-common/lib',
    'Jackalope'                             => array(__DIR__.'/../vendor/jackalope/src', __DIR__.'/../vendor/jackalope-jackrabbit/src', __DIR__.'/../vendor/jackalope-doctrine-dbal/src'),
    'PHPCR'                                 => array(__DIR__.'/../vendor/phpcr/src', __DIR__.'/../vendor/phpcr-utils/src'),

    // additional sandbox things
    'Sandbox'                        => __DIR__.'/../src',
    'Liip'                           => __DIR__.'/../vendor/bundles',
    'FOS'                            => array(__DIR__.'/../vendor/bundles', __DIR__.'/../vendor/fos'),
    'Doctrine\\Common\\DataFixtures' => __DIR__.'/../vendor/doctrine-data-fixtures/lib',

    // standard stuff
    'Symfony'          => array(__DIR__.'/../vendor/symfony/src', __DIR__.'/../vendor/bundles'),
    'Sensio'           => __DIR__.'/../vendor/bundles',
    'JMS'              => __DIR__.'/../vendor/bundles',
    'Monolog'          => __DIR__.'/../vendor/monolog/src',
    'Assetic'          => __DIR__.'/../vendor/assetic/src',
    'Metadata'         => __DIR__.'/../vendor/metadata/src',

    // menu bundle
    'Knp\\Menu'        => __DIR__.'/../vendor/knp-menu/src',
    'Knp\\Bundle'      => __DIR__.'/../vendor/bundles',

    'Sonata'           => __DIR__.'/../vendor/bundles',
    'Acme'             => __DIR__.'/../src',

));
$loader->registerPrefixes(array(
    'Twig_Extensions_' => __DIR__.'/../vendor/twig-extensions/lib',
    'Twig_'            => __DIR__.'/../vendor/twig/lib',
));

// intl
if (!function_exists('intl_get_error_code')) {
    require_once __DIR__.'/../vendor/symfony/src/Symfony/Component/Locale/Resources/stubs/functions.php';

    $loader->registerPrefixFallbacks(array(__DIR__.'/../vendor/symfony/src/Symfony/Component/Locale/Resources/stubs'));
}

$loader->registerNamespaceFallbacks(array(
    __DIR__.'/../src',
));
$loader->register();

AnnotationRegistry::registerLoader(function($class) use ($loader) {
    $loader->loadClass($class);
    return class_exists($class, false);
});
AnnotationRegistry::registerFile(__DIR__.'/../vendor/doctrine-phpcr-odm/lib/Doctrine/ODM/PHPCR/Mapping/Annotations/DoctrineAnnotations.php');

// Swiftmailer needs a special autoloader to allow
// the lazy loading of the init file (which is expensive)
require_once __DIR__.'/../vendor/swiftmailer/lib/classes/Swift.php';
Swift::registerAutoload(__DIR__.'/../vendor/swiftmailer/lib/swift_init.php');

