#!/bin/bash

cp app/config/parameters_midgard_sqlite.yml.dist app/config/parameters.yml

# Install Midgard2
./app/tests/travis_midgard_install.sh

# Install with Composer
php composer.phar update --dev

# Ensure the database is properly created
app/console doctrine:phpcr:register-system-node-types
app/console doctrine:phpcr:workspace:create sandbox_test
