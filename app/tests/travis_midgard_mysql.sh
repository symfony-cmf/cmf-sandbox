#!/bin/bash

cp app/config/parameters_midgard_mysql.yml.dist app/config/parameters.yml

# Install libgda MySQL connector
sudo apt-get install -y libgda-4.0-mysql

# Create the database
mysql -e 'create database midgard2_test;'

# Install Midgard2
./app/tests/travis_midgard_install.sh

# Install with Composer
php composer.phar update --dev

# Ensure the database is properly created
app/console doctrine:phpcr:register-system-node-types
app/console doctrine:phpcr:workspace:create sandbox_test
