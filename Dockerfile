FROM php:8.5.8-cli-alpine3.23

RUN apk add --no-cache git unzip \
    && apk add --no-cache --virtual .build-deps linux-headers \
    && docker-php-ext-install sockets \
    && apk del .build-deps

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app
