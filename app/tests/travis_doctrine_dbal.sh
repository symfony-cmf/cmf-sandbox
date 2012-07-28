#!/bin/bash

php composer.phar require jackalope/jackalope-doctrine-dbal:dev-master

mysql -e 'create database IF NOT EXISTS sandbox;' -u root

php app/console doctrine:phpcr:init:dbal -e=test
