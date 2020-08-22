#!/usr/bin/env bash
ROOT_DIR=$"$PWD/T360"
ARCHIVE_DIR="/var/www/archive/"
PRDS_DIR="/var/www/tickner360.com"

if [ !  -d "$ROOT_DIR" ]; then
 mkdir $ROOT_DIR
 echo "=> Create root folder: Done"
fi

if [ -d "$ROOT_DIR/development" ]; then
cd $ROOT_DIR/development
git pull
cd ..
echo "=> Update latest code: Done"
else
git clone --branch phi_working_branch https://github.com/360safety/development.git $ROOT_DIR/development
echo "=> Clone code: Done"
fi

if [ ! -d "$PRDS_DIR" ]; then
echo "Production directory does not exists"
exit 0
fi

BKProduction=$"$ARCHIVE_DIR/production_$(date '+%d-%m-%Y_%H-%M-%S')"
mkdir -p $BKProduction
cp -R $PRDS_DIR $BKProduction
prds_dir_name=${PRDS_DIR##*/}
if [ -d "$BKProduction/$prds_dir_name" ]; then
echo "=> Backup old production: Done"
else
echo "Backup production failed"
exit 0
fi

rm -rf $PRDS_DIR
echo "=> Remove old production: Done"

echo "=> Start build new production"
php $ROOT_DIR/development/production.build.php

if [ ! -d $PRDS_DIR ]; then
mkdir -p $PRDS_DIR
fi
cp -r $ROOT_DIR/production/development/* $PRDS_DIR

if [ ! -f "$PRDS_DIR/.env" ] && [ -f $BKProduction/production/.env ]; then
 cp $BKProduction/production/.env $PRDS_DIR
 echo "Copy .env: Done"
else
echo "Production does not include .env file, use default .env then you need to update aws credential"
cp $BKProduction/$prds_dir_name/env.default $PRDS_DIR/.env
fi

rm -rf $ROOT_DIR/production

if [ ! -d "$ROOT_DIR/production" ]; then
echo "=> Clean data build: Done"
else
echo "=> Clean data build: Failed"
exit 0
fi
echo "=> DEPLOY SUCCESS"