# syntax=docker/dockerfile:1.4

# "php" stage
FROM php:8.1-fpm-alpine AS symfony_php

# php extensions installer: https://github.com/mlocati/docker-php-extension-installer
COPY --from=mlocati/php-extension-installer --link /usr/bin/install-php-extensions /usr/local/bin/

# persistent / runtime deps
RUN apk add --no-cache \
        acl \
        gmp \
        gmp-dev \
        fcgi \
        file \
        gettext \
        git \
        jq \
        bash \
        make \
    ;

RUN set -eux; \
    install-php-extensions \
        gmp \
        intl \
    	zip \
    	apcu \
		opcache \
    ;

# https://getcomposer.org/doc/03-cli.md#composer-allow-superuser
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV PATH="${PATH}:/root/.composer/vendor/bin"

COPY --from=composer/composer:2-bin --link /composer /usr/bin/composer

RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

WORKDIR /srv/app

COPY --link . .


CMD ["php-fpm"]

