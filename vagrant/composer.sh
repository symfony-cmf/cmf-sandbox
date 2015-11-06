#!/usr/bin/env bash

exec_cmf_command() {
    su -l vagrant -c "(cd /var/www/cmf && $1)"
}

init_mysql() {
    exec_cmf_command "php app/console doctrine:database:create"
    exec_cmf_command "php app/console doctrine:phpcr:init:dbal"
}

init_jackrabbit() {
    exec_cmf_command "./jack start"
}

main() {
    storage_type=`cat /etc/storage_type.txt`

    exec_cmf_command "composer install"

    if [ $storage_type = 'mysql' ]; then
        init_mysql
    else
        init_jackrabbit
    fi

    exec_cmf_command "php app/console doctrine:phpcr:workspace:create default"
    exec_cmf_command "php app/console doctrine:phpcr:repository:init"
    exec_cmf_command "php app/console -v doctrine:phpcr:fixtures:load --no-interaction"
    exec_cmf_command "php app/console assetic:dump"

    export $storage_type

    echo "cd /var/www/cmf" >> /home/vagrant/.bashrc
}

main
