#!/usr/bin/env bash

DIR=$1
run () {
    comment="+++ "$1" +++"
    command=$2
    echo ${comment}
    echo "Command: "${command}
    ${command} > /tmp/output 2> /tmp/error
    OUT=$?
    if [ ${OUT} -eq 0 ];then
       echo "+++ DONE +++"
       cat /tmp/output
       echo
    else
       echo "+++ Errors +++"
       cat /tmp/output
       cat /tmp/error
       exit ${OUT}
    fi
}

CACHE_DIR=${DIR}var/cache/prod
#run  "Remove cache directory:" "rm -rf ${CACHE_DIR}"
run "Drop and init dbal:" "php ${DIR}bin/console --env=prod doctrine:phpcr:init:dbal --drop --force -n -vvv"
run "Init repositories:" "php ${DIR}bin/console --env=prod doctrine:phpcr:repository:init -n -vvv"
run "Load date fixtures:" "php ${DIR}bin/console --env=prod doctrine:phpcr:fixtures:load -n -vvv"
run "Warm up cache:" "php ${DIR}bin/console --env=prod cache:warmup -n --no-debug -vvv"