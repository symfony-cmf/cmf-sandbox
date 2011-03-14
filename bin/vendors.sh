#!/bin/sh

DIR=`php -r "echo realpath(dirname(\\$_SERVER['argv'][0]));"`
VENDOR=$DIR/vendor
SRC=$DIR/src

# initialization
if [ "$1" = "--reinstall" ]; then
    rm -rf $VENDOR
    rm -rf $SRC/Symfony
    rm -rf $SRC/Liip
fi

mkdir -p $VENDOR && cd $VENDOR

##
# @param destination directory (e.g. "doctrine")
# @param URL of the git remote (e.g. git://github.com/doctrine/doctrine2.git)
# @param revision to point the head (e.g. origin/HEAD)
#
install_git()
{
    INSTALL_DIR=$1
    SOURCE_URL=$2
    REV=$3

    if [ -z $REV ]; then
        REV=origin/HEAD
    fi

    if [ ! -d $INSTALL_DIR ]; then
        git clone $SOURCE_URL $INSTALL_DIR
    fi

    cd $INSTALL_DIR
    git fetch origin
    git reset --hard $REV
    git submodule update --init --recursive
    cd ..
}

# Symfony
install_git symfony git://github.com/symfony/symfony.git

# Do not update the bootstrap files. This is just a sandbox for the cmf. Look into that if you have a real project.

# Doctrine
mkdir -p doctrine
cd doctrine
install_git common git://github.com/doctrine/common.git
cd ..

# Doctrine PHPCR
install_git phpcr-odm git://github.com/doctrine/phpcr-odm.git

# Twig
install_git twig git://github.com/fabpot/Twig.git

# Twig Extensions
# ? install_git twig-extensions git://github.com/fabpot/Twig-extensions.git

# Zend Framework Log
mkdir -p zend-log/Zend
cd zend-log/Zend
install_git Log git://github.com/symfony/zend-log.git
cd ../..


### now install some bundles we use ###

cd ../src

# 3rd party bundles we depend on
mkdir -p Liip
cd Liip
install_git FunctionalTestBundle git://github.com/liip/FunctionalTestBundle.git
cd ..

mkdir -p Symfony/Bundle
cd Symfony/Bundle
install_git DoctrinePHPCRBundle git://github.com/symfony-cmf/DoctrinePHPCRBundle.git
cd ../..

mkdir -p Symfony/Cmf/Bundle
cd Symfony/Cmf/Bundle
install_git CoreBundle git://github.com/symfony-cmf/CoreBundle.git
install_git NavigationBundle git://github.com/symfony-cmf/NavigationBundle.git
cd ../../..



# Update assets
../app/console assets:install ../web/
