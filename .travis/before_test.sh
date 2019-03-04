#!/usr/bin/env bash

phpenv config-add travis.php.ini
php -ini | grep memory_limit
cp config/fixtures/phpcr_${TRANSPORT}.yaml.dist config/phpcr.yaml
composer run-script post-update-cmd
./tests/travis_${TRANSPORT}.sh
bin/console doctrine:phpcr:workspace:create sandbox_test -e test
bin/console doctrine:phpcr:repository:init -e test
