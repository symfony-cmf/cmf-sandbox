#!/bin/bash

# Install libgda MySQL connector
sudo apt-get install -y libgda-4.0-mysql

# Create the database
mysql -e 'create database midgard2_test;'

# Install Midgard2
./app/tests/travis_midgard_install.sh
