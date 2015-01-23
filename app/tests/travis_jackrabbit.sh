#!/bin/bash

composer install --prefer-dist

./vendor/jackalope/jackalope-jackrabbit/bin/jackrabbit.sh
