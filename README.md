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
    bin/vendors.sh

This will fetch the main project and all it's dependencies ( Zend, Symfony, Doctrine\PHPCR, Jackalope ... )

### Create your personal config file

You have to copy the default config.yml to fit your needs:
    cp app/config/config.yml.dist app/config/config.yml

### Install and run Apache JackRabbit

In order to run tests or application, you will need a working Jackrabbit server running and listening by default on localhost port 8080.

### Download the jackrabbit server from the official website: http://jackrabbit.apache.org/downloads.html

    mv jackrabbit-standalone-2.x.x.jar jackrabbit-standalone.jar
    java -jar jackrabbit-standalone.jar

## Try it

Open you browser on http://localhost:8080/
You should have a default jackrabbit homepage.

## Run the test suite

Tests are written with PHPUnit.

    phpunit -c app

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

## Admin interface

There is a proof-of-concept admin interface at http://cmf.lo/app_dev.php/admin
We intend to replace this either by VIE (http://bergie.github.com/VIE/) or
something with the AdminBundle.


The frontend is at http://cmf.lo/app_dev.php
