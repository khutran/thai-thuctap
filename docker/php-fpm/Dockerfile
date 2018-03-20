# https://hub.docker.com/_/php/

FROM php:7.0-fpm
LABEL maintainer="thaild <thaiattt@gmail.com>"

WORKDIR /var/www
# install the PHP extensions we need
RUN set -ex; \
    apt-get -y update; \
    apt-get install -y    libjpeg-dev libpng-dev vim;\
    docker-php-ext-configure gd --with-png-dir=/usr --with-jpeg-dir=/usr; \
    docker-php-ext-install gd mysqli pdo pdo_mysql opcache; 
