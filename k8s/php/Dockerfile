FROM php:8.1-fpm-alpine

# Locale
ENV LANG "C.UTF-8"
ENV LANGUAGE "C.UTF-8"
ENV LC_ALL "C.UTF-8"

RUN apk add --no-cache --update \
        curl-dev \
        git \
        wget \
        mysql-client \
        libzip-dev \
        supervisor \
        autoconf \
        make \
        automake \
        libtool \
        pkgconfig \
        bash \
        zlib-dev \
        icu-dev \
        g++ \
        libxml2-dev

RUN docker-php-ext-configure intl && docker-php-ext-install intl

ENV LIBRARY_PATH=/lib:/usr/lib

WORKDIR /var/www/html

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php -r "if (hash_file('sha384', 'composer-setup.php') === trim(file_get_contents('https://composer.github.io/installer.sig'))) { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" \
    && php composer-setup.php \
    && php -r "unlink('composer-setup.php');" \
    && mv composer.phar /usr/local/bin/composer

RUN docker-php-ext-install mysqli pdo pdo_mysql zip opcache && \
    docker-php-ext-enable opcache

RUN apk add --no-cache cmake freetype libpng libjpeg-turbo freetype-dev libpng-dev libjpeg-turbo-dev && \
  docker-php-ext-configure gd && \
  NPROC=$(grep -c ^processor /proc/cpuinfo 2>/dev/null || 1) && \
  docker-php-ext-install -j${NPROC} gd

RUN set -ex \
    && apk add --no-cache --virtual .phpize-deps $PHPIZE_DEPS imagemagick-dev libtool \
    && export CFLAGS="$PHP_CFLAGS" CPPFLAGS="$PHP_CPPFLAGS" LDFLAGS="$PHP_LDFLAGS" \
    && pecl install imagick-3.7.0 \
    && docker-php-ext-enable imagick \
    && apk add --no-cache --virtual .imagick-runtime-deps imagemagick \
    && apk del .phpize-deps

HEALTHCHECK --interval=5m --timeout=3s CMD curl -f http://localhost/ || exit 1

COPY bash/crontab /etc/crontabs/docker-crontab

RUN chmod 0600 /etc/crontabs/docker-crontab

RUN /usr/bin/crontab /etc/crontabs/docker-crontab

COPY . /var/www/html
COPY ./k8s/php/ini/limitation.ini /usr/local/etc/php/conf.d/limitation.ini
COPY ./k8s/php/ini/security.ini /usr/local/etc/php/conf.d/security.ini
COPY ./k8s/php/ini/upload.ini /usr/local/etc/php/conf.d/upload.ini
COPY ./docker/config/webapp/php/ini/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

RUN sed -i 's/pm.max_children = 5/pm.max_children = 816/' /usr/local/etc/php-fpm.d/www.conf
RUN sed -i 's/pm.start_servers = 2/pm.start_servers = 15/' /usr/local/etc/php-fpm.d/www.conf
RUN sed -i 's/pm.min_spare_servers = 1/pm.min_spare_servers = 15/' /usr/local/etc/php-fpm.d/www.conf
RUN sed -i 's/pm.max_spare_servers = 3/pm.max_spare_servers = 25/' /usr/local/etc/php-fpm.d/www.conf

EXPOSE 9000

COPY supervisord.conf /etc/supervisord.conf
RUN chmod +x /var/www/html/docker/config/webapp/setup.sh

RUN php -d allow_url_fopen=on /usr/local/bin/composer install --no-ansi --no-dev --no-interaction --no-progress --no-scripts --optimize-autoloader

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
