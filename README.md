# Symfony Content Management Framework Sandbox  [![Build Status](https://secure.travis-ci.org/symfony-cmf/cmf-sandbox.png)](http://travis-ci.org/symfony-cmf/cmf-sandbox)

This sandbox is a testing ground for the cmf bundles being developed.

It is based on the [Symfony Standard edition](https://github.com/symfony/symfony-standard) and adds all
cmf related bundles on top of the standard edition bundles.

Link to the [live demo](http://cmf.liip.ch)

## Getting started

You can run the sandbox on your system, or in a virtualbox VM using Vagrant. For the latter, see
"Getting started using Vagrant"

### You will need:

  * Git 1.6+
  * PHP 5.3.3+
  * php5-intl
  * phpunit 3.6+ (optional)
  * composer

## Initial setup and configuration

    git clone git://github.com/symfony-cmf/cmf-sandbox.git
    cd cmf-sandbox
    # copy parameters template and edit as needed
    cp app/config/parameters.yml.dist app/config/parameters.yml
    cp app/config/phpcr_jackrabbit.yml.dist app/config/phpcr.yml
    curl -s http://getcomposer.org/installer | php --

### Install and run Apache JackRabbit

Follow the guide in the [Jackalope Wiki](https://github.com/jackalope/jackalope/wiki/Running-a-jackrabbit-server).
You can also use a different PHPCR implementation but this is what is most tested.

    php composer.phar install

This will fetch the main project and all it's dependencies ( CMF Bundles, Symfony, Doctrine\PHPCR, Jackalope ... )
Please also adjust the ``app/config/parameters.yml`` as needed.

### Install the Doctrine DBAL provider (optional)

Instead of `phpcr_jackrabbit.yml.dist`, use the `phpcr_doctrine_dbal*.yml.dist`
files and create the database accordingly.

To have the Doctrine DBAL implementation installed, edit composer.json and replace
the line ``"jackalope/jackalope-jackrabbit": "1.0.*",`` with
``"jackalope/jackalope-doctrine-dbal": "dev-master",`` then run the update and install commands

    php composer.phar update jackalope/jackalope-doctrine-dbal
    php composer.phar install

Note: If you already ran install before changing the dependency to
jackalope-doctrine-dbal and see composer errors, remove the vendor directory
to have composer re-install everything.

To enable the meta data and node cache to improve performance first enable ``LiipDoctrineCacheBundle``
in ``app/AppKernel.php`` and then also perform:

    php composer.phar require liip/doctrine-cache-bundle:dev-master --no-update
    php composer.phar update liip/doctrine-cache-bundle

Finally uncomment the ``caches`` settings in the `phpcr.yml` as well as the ``liip_doctrine_cache``
settings in ``app/config/config.yml``.


Note: On Windows you need to run the shell as Administrator or edit the `composer.json` and
change the line `"symfony-assets-install": "symlink"` to `"symfony-assets-install": ""`
If you fail to do this you might receive:

    [Symfony\Component\Filesystem\Exception\IOException]
    Unable to create symlink due to error code 1314: 'A required privilege is not held by the client'. Do you have the required Administrator-rights?

Then, create the database and tables and set up the default workspace using

    app/console doctrine:database:create
    app/console doctrine:phpcr:init:dbal

### Install the Midgard2 PHPCR provider (optional)

If you want to run the CMF sandbox with the [Midgard2 PHPCR provider](http://midgard-project.org/phpcr/)
instead of Jackrabbit, you need to install the [`midgard2` PHP extension](http://midgard-project.org/midgard2/#download).
On current debian / ubuntu systems, this is simply done with ``sudo apt-get install php5-midgard2``, on OS X
``sudo port install php5-midgard2`` resp. ``brew install midgard2-php``.

You also need to download [`midgard_tree_node.xml`](https://raw.github.com/midgardproject/phpcr-midgard2/master/data/share/schema/midgard_tree_node.xml)
and [`midgard_namespace_registry.xml`](https://github.com/midgardproject/phpcr-midgard2/raw/master/data/share/schema/midgard_namespace_registry.xml)
schema files, and place them into `/usr/share/midgard2/schema` (note: this path may be different if you built Midgard2 yourself. It is
basically `$PREFIX/share/midgard2/schema`).

To have the midgard phpcr implementation installed run the following additional command

    php composer.phar require midgard/phpcr:dev-master --no-update
    php composer.phar update midgard/phpcr

Finally, instead of `phpcr_jackrabbit.yml.dist`, use one of the `phpcr_midgard_*.yml.dist` files.

## Prepare the phpcr repository

First you need to create a workspace that will hold the data for the sandbox.
The default parameters.yml defines the workspace to be 'default'. You can
change this of course. If you do, f.e. to 'sandbox, also run the following command:

    app/console doctrine:phpcr:workspace:create sandbox

Once your workspace is set up, you need to [register the node types](https://github.com/doctrine/phpcr-odm/wiki/Custom-node-type-phpcr%3Amanaged)
for phpcr-odm:

    app/console doctrine:phpcr:repository:init

## Import the fixtures

The admin backend is still in an early stage. Until it improves, the easiest is
to programmatically create data. The best way to do that is with the doctrine
data fixtures. The DoctrinePHPCRBundle included in the symfony-cmf repository
provides a command to load fixtures.

    app/console -v doctrine:phpcr:fixtures:load

Run this to load the fixtures from the Sandbox MainBundle.

## Access by web browser

Create an apache virtual host entry along the lines of

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

    app/console cache:warmup --env=prod --no-debug
    app/console assetic:dump --env=prod --no-debug

# Getting started using Vagrant

please checkout the [README.md](cmf-sandbox/tree/master/vagrant) in the vagrant/ folder of the project

# Other hints

## Console

The PHPCR ODM Bundle provides a couple of useful commands in the doctrine:phpcr namespace.
Type app/console to see them all.

## Admin interface

There is a proof-of-concept admin interface using the SonataPhpcrAdminBundle at
http://cmf.lo/app_dev.php/admin/dashboard

Basically you have paginated lists for two types of documents. You create new documents, edit and delete them.
Some filtering is available in the list. This bundle is an implementation of
[Sonata Admin Bundle](https://github.com/sonata-project/SonataAdminBundle)

At the moment there is no notion of parents and sons in the admin bundle.

## Run the test suite

Functional tests are written with PHPUnit. Note that Bundles and Components are tested independently.

    app/console doctrine:phpcr:workspace:create sandbox_test
    phpunit -c app

[![Build Status](https://secure.travis-ci.org/symfony-cmf/cmf-sandbox.png?branch=master)](http://travis-ci.org/symfony-cmf/cmf-sandbox)
