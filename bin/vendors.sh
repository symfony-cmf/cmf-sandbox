#!/bin/sh

DIR=`php -r "echo realpath(dirname(\\$_SERVER['argv'][0]));"`
VENDOR=$DIR/vendor
SRC=$DIR/src

# initialization
if [ "$1" = "--reinstall" ]; then
    rm -rf $VENDOR
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
    cd ..
}

# Symfony
install_git symfony git://github.com/symfony/symfony.git

# Do not update the bootstrap files. This is just a sandbox for the cmf. Look into that if you have a real project.

# Doctrine
#mkdir -p doctrine
#cd doctrine
#install_git common git://github.com/doctrine/common.git
#cd ..

# Twig
install_git twig git://github.com/fabpot/Twig.git

# Twig Extensions
install_git twig-extensions git://github.com/fabpot/Twig-extensions.git

# Monolog
install_git monolog git://github.com/Seldaek/monolog.git

# Doctrine PHPCR
install_git doctrine-phpcr-odm git://github.com/doctrine/phpcr-odm.git
cd $VENDOR/doctrine-phpcr-odm
git submodule update --init --recursive
cd $VENDOR

mkdir -p bundles/Symfony/Bundle
cd $VENDOR/bundles/Symfony/Bundle
install_git DoctrinePHPCRBundle git://github.com/symfony-cmf/DoctrinePHPCRBundle.git
install_git DoctrineFixturesBundle git://github.com/symfony/DoctrineFixturesBundle.git
cd $VENDOR

install_git doctrine-data-fixtures git://github.com/doctrine/data-fixtures.git


# functional test helpers
mkdir -p bundles/Liip
cd bundles/Liip
install_git FunctionalTestBundle git://github.com/liip/LiipFunctionalTestBundle.git
cd ../..


### now install the cmf bundles ###

cd ../src

mkdir -p Symfony/Cmf/Bundle
cd Symfony/Cmf/Bundle
install_git CoreBundle git://github.com/symfony-cmf/CoreBundle.git
install_git NavigationBundle git://github.com/symfony-cmf/NavigationBundle.git
install_git ContentBundle git://github.com/symfony-cmf/ContentBundle.git
install_git PhpcrCommandsBundle git://github.com/symfony-cmf/PhpcrCommandsBundle.git
cd ../../..



# Update assets
../app/console assets:install ../web
