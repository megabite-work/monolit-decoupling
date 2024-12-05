FROM php:8.2-fpm-alpine3.20 AS base

ARG PUID=1000
ARG PGID=1000

COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer
COPY php.ini /usr/local/etc/php/php.ini

RUN apk add --no-cache \
    curl \
    git \
    supervisor \
    build-base \
    zlib-dev \
    oniguruma-dev \
    autoconf \
    bash \
    zip \
    libzip-dev \
    icu-dev \
    shadow \
    rabbitmq-c-dev \
    openssl-dev \
    --update linux-headers && \
    pecl install amqp && \
    docker-php-ext-enable amqp && \
    docker-php-ext-configure intl && \
    docker-php-ext-install \
    intl \
    mysqli \
    pdo_mysql \
    mbstring \
    pcntl \
    zip && \
    groupmod -o -g ${PGID} www-data && \
    usermod -o -u ${PUID} -g www-data www-data
    
COPY supervisor/supervisord.conf /etc/supervisor/supervisord.conf

ENTRYPOINT ["/usr/bin/supervisord", "-c", "/etc/supervisor/supervisord.conf"]

FROM base AS dev

COPY xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

RUN pecl install xdebug && docker-php-ext-enable xdebug


FROM base AS prod

COPY opcache.ini /usr/local/etc/php/conf.d/opcache.ini

RUN docker-php-ext-install opcache
