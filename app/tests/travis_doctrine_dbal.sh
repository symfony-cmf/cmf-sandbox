#!/bin/bash

composer install --prefer-dist

mysql -e 'create database IF NOT EXISTS sandbox;' -u root

php app/console doctrine:phpcr:init:dbal -e=test --force
