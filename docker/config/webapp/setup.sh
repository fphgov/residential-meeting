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

  php -d allow_url_fopen=on /usr/local/bin/composer install

  composer db-update

  chmod 0644 bin/cron/notifications.php
fi

if [ $MODE = "production" ]; then
  cd /var/www/html

  php -d allow_url_fopen=on /usr/local/bin/composer install --no-ansi --no-dev --no-interaction --no-progress --no-scripts --optimize-autoloader

  composer db-update

  chmod 0644 bin/cron/notifications.php
fi

mkdir -p data/cache/DoctrineEntityProxy
chmod 777 -R data/cache/DoctrineEntityProxy
chmod 777 -R public/files/
