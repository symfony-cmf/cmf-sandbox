#!/bin/bash
cp app/config/parameters.yml.dist app/config/parameters.yml
php composer.phar update
./vendor/jackalope/jackalope-jackrabbit/bin/jackrabbit.sh

php app/console doctrine:phpcr:workspace:create sandbox_test -e=test
