#!/usr/bin/env bash

DIR=$1
run () {
    comment="+++ "$1" +++"
    command=$2
    echo ${comment}
    echo "Command: "${command}
    ${command}
    OUT=$?
    if [ ${OUT} -eq 0 ];then
       echo "+++ DONE +++"
       echo
    else
       echo "+++ Errors +++"
       exit ${OUT}
    fi
}

CACHE_DIR=${DIR}var/cache/prod
#run  "Remove cache directory:" "rm -rf ${CACHE_DIR}"
run "Drop and init dbal:" "php ${DIR}bin/console --env=prod doctrine:phpcr:init:dbal --drop --force -n"
run "Init repositories:" "php ${DIR}bin/console --env=prod doctrine:phpcr:repository:init -n"
run "Load date fixtures:" "php ${DIR}bin/console --env=prod doctrine:phpcr:fixtures:load -n"
run "Warm up cache:" "php ${DIR}bin/console --env=prod cache:warmup -n --no-debug"