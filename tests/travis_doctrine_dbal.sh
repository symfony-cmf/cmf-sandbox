#!/bin/bash

mysql -e 'create database IF NOT EXISTS sandbox;' -u root

php bin/console doctrine:phpcr:init:dbal -e=test --force
