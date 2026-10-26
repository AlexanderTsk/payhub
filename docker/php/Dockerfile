#------ Base ------
FROM php:8.4-fpm-alpine AS base

RUN apk add --no-cache \
        icu-libs \
        libzip \
        libpq \
    && apk add --no-cache --virtual .build-deps \
        $PHPIZE_DEPS \
        icu-dev \
        libzip-dev \
        libpq-dev \
    && docker-php-ext-install intl pdo_pgsql zip opcache \
    && pecl install apcu \
    && docker-php-ext-enable apcu \
    && apk del .build-deps

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY docker/php/conf.d/app.ini /usr/local/etc/php/conf.d/zz-app.ini

WORKDIR /app

#-------- Development --------
FROM base AS dev

RUN cp "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

RUN apk add --no-cache --virtual .build-deps \
        $PHPIZE_DEPS \
        linux-headers \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && apk del .build-deps

COPY docker/php/conf.d/xdebug.ini /usr/local/etc/php/conf.d/zz-xdebug.ini

#-------- Vendor --------
FROM base AS vendor

COPY payhub/composer.json payhub/composer.lock payhub/symfony.lock ./

RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist --no-interaction --no-progress

#-------- Production --------
FROM base AS prod

ENV APP_ENV=prod APP_DEBUG=0

COPY docker/php/conf.d/prod.ini /usr/local/etc/php/conf.d/zzz-prod.ini
COPY --from=vendor  /app/vendor  ./vendor
RUN cp "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
COPY payhub/ ./

RUN composer dump-autoload --no-dev --classmap-authoritative \
    && composer dump-env prod \
    && composer run-script --no-dev post-install-cmd \
    && chown -R www-data:www-data var

USER www-data