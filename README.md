# Symfony Content Management Framework Sandbox

This sandbox is a testing ground for the cmf bundles being developped.

It is based on the [Symfony Standard edition](https://github.com/symfony/symfony-standard) and adds all cmf related bundles on top of the standard edition bundles.


## Getting started

### You will need:
  * Git 1.6+
  * PHP 5.3.2+
  * phpUnit 3.5+ (optional)

## Get the code

    git clone git://github.com/symfony-cmf/cmf-sandbox.git
    cd cmf-sandbox
    # we skipped the web installer for now
    # copy parameters template and edit as needed
    cp app/config/parameters.yml.dist app/config/parameters.yml
    bin/vendors install

This will fetch the main project and all it's dependencies ( Cmf Bundles, Symfony, Doctrine\PHPCR, Jackalope ... )

### Install and run Apache JackRabbit

Follow the guide in the [Jackalope Wiki](https://github.com/jackalope/jackalope/wiki/Running-a-jackrabbit-server)
and then [register the node types](https://github.com/doctrine/phpcr-odm/wiki/Custom-node-type-phpcr%3Amanaged) for phpcr-odm:

    app/console doctrine:phpcr:register-system-node-types

## Import the fixtures

We currently do not yet have an edit backend. Until somebody builds one, you
can only programmatically create data. The best way to do that is with the
doctrine data fixtures. The DoctrinePHPCRBundle included in the symfony-cmf
repository provides a command to load fixtures.

    app/console -v doctrine:phpcr:fixtures:load --path=src/Sandbox/MainBundle/Resources/data/fixtures/ --purge=true

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

# Usage

## Console

The PHPCR ODM Bundle provides a couple of useful commands in the doctrine:phpcr namespace.
Type app/console to see them all.

## Admin interface

THIS IS CURRENTLY BROKEN as it is based on the old form framework.

There is a proof-of-concept admin interface at http://cmf.lo/app_dev.php/admin

We intend to replace this either by VIE (http://bergie.github.com/VIE/) or
something with one of the AdminBundle s.


## Run the test suite

Tests are written with PHPUnit.

TESTS ARE CURRENTLY BROKEN

    phpunit -c app

