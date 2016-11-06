# Symfony Content Management Framework Sandbox  

[![Build Status](https://travis-ci.org/symfony-cmf/cmf-sandbox.svg?branch=master)](https://travis-ci.org/symfony-cmf/cmf-sandbox)
[![StyleCI](https://styleci.io/repos/1316499/shield)](https://styleci.io/repos/1316499)
[![Dependency Status](https://www.versioneye.com/php/symfony-cmf:sandbox/badge.svg)](https://www.versioneye.com/php/symfony-cmf:sandbox)

This sandbox is a testing ground for the cmf bundles being developed.

It is based on the [Symfony Standard edition](https://github.com/symfony/symfony-standard) and adds all
cmf related bundles on top of the standard edition bundles.

Link to the [live demo](http://sandbox.cmf.symfony.com/)

## Getting started

You can run the sandbox on your system, or in a virtualbox VM using Vagrant. For the latter, see
"Getting started using Vagrant"

### You will need:

  * PHP 5.3.9+ (with intl extension)
  * PHPUnit 3.6+ (optional)
  * Composer

## Initial setup and configuration

    git clone git://github.com/symfony-cmf/cmf-sandbox.git
    cd cmf-sandbox
    curl -s http://getcomposer.org/installer | php --
    php composer.phar install

At the end of the installation you will be interactively asked several configuration
questions. Note that by default you will end up with a configuration using
SQLite and Doctrine DBAL for storage. If you want to adjust the configuration
to use Jackrabbit please look at the following section.

### Install and run Apache JackRabbit

Follow the guide in the [Jackalope Wiki](https://github.com/jackalope/jackalope/wiki/Running-a-jackrabbit-server).
You can also use a different PHPCR implementation but this is the most solid
implementation.

Once you have that, copy the default jackalope-jackrabbit configuration file,
adjust it as needed and install the dependencies with composer:

    cp app/config/phpcr_jackrabbit.yml.dist app/config/phpcr.yml

The last command will fetch the main project and all its dependencies (CMF
Bundles, Symfony, Doctrine\PHPCR, Jackalope ... ). You might want to have a look
at the ``app/config/parameters.yml`` and adjust as needed.

### Install the Doctrine DBAL provider (optional)

Instead of `phpcr_jackrabbit.yml.dist`, use the `phpcr_doctrine_dbal*.yml.dist`
files and create the database accordingly. If you have the PHP sqlite extension
available, this is the simplest to quickly try out the CMF. Copy the file
and then install the dependencies:

    cp app/config/phpcr_doctrine_dbal.yml.dist app/config/phpcr.yml

The Doctrine DBAL implementation is installed by default already along side the Jackrabbit implementation.

To disable the meta data and node cache for debugging comment the ``caches`` settings in the `phpcr.yml`.

Then, create the database and tables and set up the default workspace using:

    php bin/console doctrine:database:create
    php bin/console doctrine:phpcr:init:dbal

## Prepare the PHPCR repository

First you need to create a workspace that will hold the data for the sandbox.
The default parameters.yml defines the workspace to be 'default'. You can
change this of course. If you do, f.e. to 'sandbox, also run the following command:

    php bin/console doctrine:phpcr:workspace:create sandbox

Once your workspace is set up, you need to [register the node types](https://github.com/doctrine/phpcr-odm/wiki/Custom-node-type-phpcr%3Amanaged)
for PHPCR-ODM:

    php bin/console doctrine:phpcr:repository:init

## Import the fixtures

The admin backend is still in an early stage. Until it improves, the easiest is
to programmatically create data. The best way to do that is with the doctrine
data fixtures. The DoctrinePHPCRBundle included in the symfony-cmf repository
provides a command to load fixtures:

    php bin/console -v doctrine:phpcr:fixtures:load

Run this to load the fixtures from the Sandbox AppBundle.

## Setup filesystem permissions

As with any Symfony2 installation, you need to set up some filesystem permissions.
A good guide is in the [Symfony2 installation guide](http://symfony.com/doc/current/book/installation.html#configuration-and-setup).
If you use the default setup, an sqlite database will be created at `app/app.sqlite`.
You need to set up permissions for this file and the app/ folder with the method
you chose from the installation guide.

If you just want to move on and try out the sandbox for now, you can
run:

    sudo chmod -R 777 app/

## Access by web browser

Create an apache virtual host entry along the lines of:

    <Virtualhost *:80>
        Servername cmf.lo
        DocumentRoot /path/to/symfony-cmf/cmf-sandbox/web
        <Directory /path/to/symfony-cmf/cmf-sandbox>
            AllowOverride All
        </Directory>
    </Virtualhost>

And add an entry to your hosts file for cmf.lo

If you are running Symfony2 for the first time, run http://cmf.lo/config.php to ensure your system settings have been
setup inline with the expected behaviour of the Symfony2 framework.

Note however that "Configure your Symfony Application online" is not supported in the sandbox.

Then point your browser to http://cmf.lo/app_dev.php

## Production environment

In order to run the sandbox in production mode at http://cmf.lo/
you need to generate the doctrine proxies and dump the assetic assets:

    php bin/console cache:warmup --env=prod --no-debug
    php bin/console assetic:dump --env=prod --no-debug

# Getting started using Vagrant

please checkout the [README.md](vagrant) in the vagrant/ folder of the project

# Other hints

## Console

The PHPCR ODM Bundle provides a couple of useful commands in the doctrine:phpcr namespace.
Type bin/console to see them all.

## Admin interface

There is a proof-of-concept admin interface using the SonataPhpcrAdminBundle at
http://cmf.lo/app_dev.php/admin/dashboard

Basically you have paginated lists for two types of documents. You create new documents, edit and delete them.
Some filtering is available in the list. This bundle is an implementation of
[Sonata Admin Bundle](https://github.com/sonata-project/SonataAdminBundle)

At the moment there is no notion of parents and sons in the admin bundle.

## Run the test suite

Functional tests are written with PHPUnit. Note that Bundles and Components are tested independently:

    php bin/console doctrine:phpcr:workspace:create sandbox_test
    phpunit -c app

## Remove demo configuration

If you start a project from the sandbox, remove ```.sensiolabs.yml``` as its not a good example for production use.
