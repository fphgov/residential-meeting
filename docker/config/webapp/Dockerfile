FROM php:7-fpm-alpine

ARG WITH_XDEBUG=true

RUN apk add --no-cache --update git wget mysql-client libzip-dev

RUN apk add --no-cache --update zlib-dev icu-dev g++ libxml2-dev && docker-php-ext-configure intl && docker-php-ext-install intl

ENV LIBRARY_PATH=/lib:/usr/lib

WORKDIR /var/www/html

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php -r "if (hash_file('sha384', 'composer-setup.php') === trim(file_get_contents('https://composer.github.io/installer.sig'))) { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" \
    && php composer-setup.php \
    && php -r "unlink('composer-setup.php');" \
    && mv composer.phar /usr/local/bin/composer

RUN docker-php-ext-install mysqli pdo pdo_mysql zip intl && \
    docker-php-ext-enable mysqli pdo pdo_mysql zip intl

RUN apk add --no-cache cmake freetype libpng libjpeg-turbo freetype-dev libpng-dev libjpeg-turbo-dev && \
  docker-php-ext-configure gd && \
  NPROC=$(grep -c ^processor /proc/cpuinfo 2>/dev/null || 1) && \
  docker-php-ext-install -j${NPROC} gd

# Install Xdebug
RUN if [ $WITH_XDEBUG = "true" ]; then \
  apk add --no-cache $PHPIZE_DEPS && pecl install xdebug; \
  docker-php-ext-enable xdebug; \
  echo "error_reporting = E_ALL" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
  echo "display_startup_errors = On" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
  echo "display_errors = On" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
  echo "xdebug.remote_enable=1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
  echo "xdebug.var_display_max_data=512" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
  echo "xdebug.var_display_max_children=128" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
  echo "xdebug.var_display_max_depth=3" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
fi;

HEALTHCHECK --interval=5m --timeout=3s CMD curl -f http://localhost/ || exit 1

EXPOSE 9001

CMD ["php-fpm"]
