# Symfony Content Management Framework Sandbox

This sandbox is a testing ground for the cmf bundles being developped.


## Getting started

### You will need:
  * Git 1.6+
  * PHP 5.3.2+
  * phpUnit 3.5+ (optional)

## Get the code

    git clone git://github.com/symfony-cmf/cmf-sandbox.git
    cd cmf-sandbox
    cp app/config.yml.dist app/config.yml #maybe need to edit if defaults are not ok
    bin/vendors.sh

This will fetch the main project and all it's dependencies ( Cmf Bundles, Symfony, Doctrine\PHPCR, Jackalope ... )

### Install and run Apache JackRabbit

Follow the guide at
https://github.com/symfony-cmf/symfony-cmf/wiki/Running-a-Jackrabbit-server
and then register the node types for phpcr-odm
https://github.com/doctrine/phpcr-odm/wiki/Custom-node-type-phpcr%3Amanaged

## Run the test suite

Tests are written with PHPUnit.

TESTS ARE CURRENTLY BROKEN

    phpunit -c app


## Import the fixtures

app/console -v phpcr:fixtures:load --path=src/Sandbox/MainBundle/Resources/data/fixtures/ --purge=true


## Access by web browser

Create an apache virtual host entry along the lines of
<Virtualhost *:80>
    Servername cmf.lo
    DocumentRoot /path/to/symfony-cmf/cmf-sandbox/web
    <Directory /path/to/symfony-cmf/cmf-sandox>
        AllowOverride All
    </Directory>
</Virtualhost>

And add an entry to your hosts file for cmf.lo

Then point your browser to http://cmf.lo/app_dev.php

## Admin interface

THIS IS CURRENTLY BROKEN as it is based on the old form framework.

There is a proof-of-concept admin interface at http://cmf.lo/app_dev.php/admin
We intend to replace this either by VIE (http://bergie.github.com/VIE/) or
something with the AdminBundle.

