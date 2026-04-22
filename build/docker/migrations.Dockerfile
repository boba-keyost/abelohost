FROM alpine as build

WORKDIR /build

RUN apk add --no-cache \
    wget \
    php85 \
    php85-phar \
    php85-mysqli \
    php85-pdo \
    php85-pdo_mysql \
    php85-mbstring \
    php85-dom \
    php85-simplexml \
    php85-openssl

RUN ln -s /usr/bin/php85 /usr/bin/php

RUN wget https://raw.githubusercontent.com/composer/getcomposer.org/f3108f64b4e1c1ce6eb462b159956461592b3e3e/web/installer -O - -q | php -- --quiet

RUN ln -s /build/composer.phar /usr/bin/composer

COPY migrate/ /app

WORKDIR /app

RUN  composer install \
  --optimize-autoloader \
  --no-interaction \
  --no-progress
