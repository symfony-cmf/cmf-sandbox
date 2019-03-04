#!/bin/bash

mysql -e 'create database IF NOT EXISTS sandbox;' -u root9

php bin/console doctrine:phpcr:init:dbal -e test --force
