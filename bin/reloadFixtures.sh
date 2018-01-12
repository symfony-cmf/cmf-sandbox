#!/usr/bin/env bash

DIR=$1
run () {
    comment="+++ "$1" +++"
    command=$2
    echo ${comment}
    ${command} > output 2> error
    OUT=$?
    if [ ${OUT} -eq 0 ];then
       echo "+++ DONE +++"
       cat output
       echo
    else
       echo "+++ Errors +++"
       cat output
       cat error
       exit ${OUT}
    fi
}

run  "Remove cache directory:" "rm -rf ${DIR}var/cache/prod"
run "Drop and init dbal:" "${DIR}bin/console --env=prod doctrine:phpcr:init:dbal --drop --force -n"
run "Init repositories:" "${DIR}bin/console --env=prod doctrine:phpcr:repository:init -n"
run "Load date fixtures:" "${DIR}bin/console --env=prod doctrine:phpcr:fixtures:load -n"
run "Warm up cache:" "${DIR}bin/console --env=prod cache:warmup -n --no-debug"