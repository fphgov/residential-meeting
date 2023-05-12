#!/bin/sh

unset MODE

while getopts 'm:' c
do
  case $c in
    m) MODE="$OPTARG" ;;
  esac
done

if [ $MODE = "development" ]; then
  cd /var/www/html

  composer db-update

  chmod 0644 bin/cron/notifications.php
fi

if [ $MODE = "production" ]; then
  cd /var/www/html

  composer db-update

  chmod 0644 bin/cron/notifications.php
fi

mkdir -p data/cache/DoctrineEntityProxy
chmod 777 -R data/cache/DoctrineEntityProxy
