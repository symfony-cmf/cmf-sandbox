<?php

use Symfony\Component\ClassLoader\UniversalClassLoader;

$loader = new UniversalClassLoader();
$loader->registerNamespaces(array(
    'Symfony'                        => __DIR__.'/../vendor/symfony/src',
    'Bundle'                         => __DIR__.'/../src',
    'Doctrine\\Common\\DataFixtures' => __DIR__.'/../vendor/doctrine-data-fixtures/lib',
    'Doctrine\\Common'               => __DIR__.'/../vendor/doctrine-common/lib',
    'Doctrine\\DBAL\\Migrations'     => __DIR__.'/../vendor/doctrine-migrations/lib',
    'Doctrine\\MongoDB'              => __DIR__.'/../vendor/doctrine-mongodb/lib',
    'Doctrine\\ODM\\MongoDB'         => __DIR__.'/../vendor/doctrine-mongodb-odm/lib',
    'Doctrine\\DBAL'                 => __DIR__.'/../vendor/doctrine-dbal/lib',
    'Doctrine\\ODM\\PHPCR'           => __DIR__.'/../doctrine-phpcr/lib',
    'Doctrine'                       => __DIR__.'/../vendor/doctrine/lib',
    'Zend'                           => __DIR__.'/../vendor/zend/library',
    'Jackalope'                      => __DIR__.'/../vendor/doctrine-phpcr/lib/vendor/jackalope/src',
    'PHPCR'                          => __DIR__.'/../vendor/doctrine-phpcr/lib/vendor/jackalope/lib/phpcr/src',
));
$loader->registerPrefixes(array(
    'Twig_Extensions_' => __DIR__.'/../vendor/twig-extensions/lib',
    'Twig_'            => __DIR__.'/../vendor/twig/lib',
    'Swift_'           => __DIR__.'/../vendor/swiftmailer/lib/classes',
));
$loader->register();
