ARG PHP_VERSION=8.3.4
ARG PHP_DEPS_VERSION=basic-php
ARG NGINX_VERSION=1.24.0
ARG APCU_VERSION=5.1.22
ARG AMQP_VERSION=1.11.0
ARG XDEBUG_VERSION=3.3.1

################################################
#          >>> Docker basic-php image          #
################################################
FROM php:${PHP_VERSION}-fpm-alpine3.18 AS basic-php

WORKDIR /srv

ENV USER=sensei
ENV UID=1000
ENV GID=1000
ARG APCU_VERSION
ARG AMQP_VERSION
ARG COMPOSER_AUTH
ENV FPM.request_terminate_timeout=30s

#system
RUN addgroup --gid "$GID" "$USER"
RUN adduser \
    --disabled-password \
    --gecos "" \
    --home "$(pwd)"\
    --ingroup "$USER" \
    --no-create-home \
    --uid "$UID" \
    "$USER"
RUN chown -R $USER: /usr/local/etc/php
RUN chown -R $USER: /usr/local/etc/php-fpm.d/

# persistent / runtime deps
RUN apk add --no-cache \
        acl \
        fcgi \
        file \
        gettext \
        git \
        tzdata \
        linux-headers \
    ;

RUN set -eux; \
    apk add --no-cache --virtual .build-deps \
        $PHPIZE_DEPS \
        icu-dev \
        icu-data-full \
        libzip-dev \
        rabbitmq-c-dev \
        libsodium-dev \
        openssl-dev \
        curl-dev \
        zlib-dev \
        libgcrypt-dev \
        gmp-dev \
    ; \
    \
    docker-php-ext-install -j$(nproc) \
        intl \
        pdo_mysql \
        bcmath \
        curl \
        zip \
        opcache \
        gmp \
    ; \
    pecl install \
        apcu-${APCU_VERSION} amqp-${AMQP_VERSION} \
    ; \
    pecl clear-cache; \
    docker-php-ext-enable \
        apcu \
        opcache \
        amqp \
    ; \
    \
    runDeps="$( \
        scanelf --needed --nobanner --format '%n#p' --recursive /usr/local/lib/php/extensions \
            | tr ',' '\n' \
            | sort -u \
            | awk 'system("[ -e /usr/local/lib/" $1 " ]") == 0 { next } { print "so:" $1 }' \
    )"; \
    apk add --no-cache --virtual .api-phpexts-rundeps $runDeps; \
    \
    apk del .build-deps

RUN mkdir -p /srv/first
RUN chown -R $USER: /srv

USER $USER

COPY --chown=$USER:$USER --from=composer:2.6.5 /usr/bin/composer /usr/bin/composer

# https://getcomposer.org/doc/03-cli.md#composer-allow-superuser
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV PATH="${PATH}:/root/.composer/vendor/bin"

WORKDIR /srv/first
################################################
#          <<< Docker basic-php image          #
################################################

########################## composer-vendors ##########################
FROM ${PHP_DEPS_VERSION} AS composer-vendors

WORKDIR /srv

COPY --chown=$USER:$USER composer.json composer.lock symfony.lock ./

RUN set -eux; \
	composer install --prefer-dist --no-dev --no-scripts --no-progress; \
	composer clear-cache
########################## composer-vendors ##########################

########################## php ##########################
FROM ${PHP_DEPS_VERSION} AS php

RUN ln -s $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini

RUN set -eux; \
    { \
        echo 'pm.max_children = 50'; \
        echo 'pm.start_servers = 25'; \
        echo 'pm.min_spare_servers = 5'; \
        echo 'pm.max_spare_servers = 25'; \
        echo 'pm.max_requests = 10000'; \
    } >> /usr/local/etc/php-fpm.d/zz-docker.conf
COPY --chown=$USER:$USER build/package/php-fpm/conf.d/www.conf /usr/local/etc/php-fpm.d/docker.conf
COPY --chown=$USER:$USER build/package/php-fpm/conf.d/api.prod.ini $PHP_INI_DIR/conf.d/api.ini

RUN set -eux; \
    { \
        echo '[www]'; \
        echo 'ping.path = /ping'; \
    } | tee /usr/local/etc/php-fpm.d/docker-healthcheck.conf

# copy only specifically what we need
COPY --chown=$USER:$USER .env ./
RUN true
COPY --chown=$USER:$USER bin ./bin
RUN true
COPY --chown=$USER:$USER config ./config
RUN true
COPY --chown=$USER:$USER docs/api ./docs/api
RUN true
COPY --chown=$USER:$USER public ./public
RUN true
COPY --chown=$USER:$USER src ./src
RUN true
COPY --chown=$USER:$USER templates ./templates
RUN true
COPY --chown=$USER:$USER translations ./translations
RUN true

# prevent the reinstallation of vendors at every changes in the source code
COPY --chown=$USER:$USER  composer.json composer.lock symfony.lock ./
COPY --chown=$USER:$USER --from=composer-vendors /srv/vendor vendor/

RUN set -eux; \
    mkdir -p var/cache var/log; \
    composer dump-autoload --classmap-authoritative --no-dev --apcu; \
    composer dump-env prod; \
    composer run-script --no-dev post-install-cmd; \
    chmod +x bin/console; sync

VOLUME /srv/first/var

COPY --chown=$USER:$USER build/package/php-fpm/docker-healthcheck.sh /usr/local/bin/docker-healthcheck

HEALTHCHECK --interval=10s --timeout=3s --retries=3 CMD ["docker-healthcheck"]

COPY --chown=$USER:$USER build/package/php-fpm/docker-entrypoint.sh /usr/local/bin/docker-entrypoint

USER root

RUN apk add --no-cache fcgi
RUN wget -O /usr/bin/php-fpm-healthcheck \
    https://raw.githubusercontent.com/renatomefi/php-fpm-healthcheck/master/php-fpm-healthcheck \
    && chmod +x /usr/bin/php-fpm-healthcheck \
    && chown $USER: /usr/bin/php-fpm-healthcheck;

USER $USER

ENTRYPOINT ["docker-entrypoint"]

CMD ["php-fpm"]

EXPOSE 9000

######################## php-dev ########################
FROM php AS php-dev

ARG XDEBUG_VERSION

USER root

RUN set -eux; \
    apk add --no-cache --virtual .build-deps $PHPIZE_DEPS; \
    apk add --update linux-headers; \
    pecl install xdebug-$XDEBUG_VERSION; \
    docker-php-ext-enable xdebug; \
    apk del .build-deps

COPY --chown=$USER:$USER build/package/php-fpm/conf.d/www.dev.conf /usr/local/etc/php-fpm.d/docker.conf
COPY --chown=$USER:$USER build/package/php-fpm/conf.d/api.dev.ini $PHP_INI_DIR/conf.d/api.ini

RUN chown -R $USER:$USER /usr/local/etc/php
RUN chown -R $USER:$USER /usr/local/etc/php-fpm.d/

USER $USER

######################## nginx #########################
FROM nginx:${NGINX_VERSION}-alpine AS nginx

ARG COMPOSER_AUTH

ENV USER=sensei
ENV UID=1000
ENV GID=1000

RUN apk add --no-cache tzdata;
RUN addgroup --gid "$GID" "$USER"
RUN adduser \
    --disabled-password \
    --gecos "" \
    --home "$(pwd)"\
    --ingroup "$USER" \
    --no-create-home \
    --uid "$UID" \
    "$USER"
RUN chown -R $USER: /var/cache/nginx
RUN chown -R $USER: /etc/nginx
RUN chown -R $USER: /srv
USER $USER

COPY --chown=$USER:$USER build/package/nginx/nginx.conf /etc/nginx/nginx.conf
COPY --chown=$USER:$USER build/package/nginx/conf.d/default.conf /etc/nginx/templates/default.conf.template

WORKDIR /srv/first/public

COPY --chown=$USER:$USER --from=php /srv/first/public ./

ENV API_NGINX_FASTCGI_PASS="php:9000"

EXPOSE 80
