#!/bin/bash
cp app/config/parameters.yml.dist app/config/parameters.yml
php composer.phar install --dev
./vendor/jackalope/jackalope-jackrabbit/bin/jackrabbit.sh
