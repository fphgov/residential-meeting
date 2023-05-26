FROM php:8.1-cli-alpine AS builder

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

COPY . .
RUN php -d allow_url_fopen=on /usr/local/bin/composer install --no-ansi --no-dev --no-interaction --no-progress --no-scripts --optimize-autoloader

FROM fphgov/nginx AS app

COPY  ./k8s/nginx /etc/nginx/conf.d
COPY --from=builder /var/www/html /var/www/html
