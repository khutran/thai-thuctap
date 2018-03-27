# https://hub.docker.com/_/php/

FROM  php:7.1-fpm
LABEL maintainer="thaild <thaiattt@gmail.com>"

WORKDIR /var/www

# Install PHP extensions for mautic
RUN apt-get update && apt-get install --no-install-recommends -y \
    cron \
    libc-client-dev \
    libicu-dev \
    libkrb5-dev \
    libmcrypt-dev \
    libssl-dev \
    unzip \
    zip \
    && rm /etc/cron.daily/*
RUN docker-php-ext-configure imap --with-imap --with-imap-ssl --with-kerberos \
    && docker-php-ext-install imap intl mbstring mcrypt zip

# install the PHP extensions we need
RUN set -ex; \
    apt-get -y update; \
    apt-get install -y libjpeg-dev libpng-dev vim;\
    docker-php-ext-configure gd --with-png-dir=/usr --with-jpeg-dir=/usr; \
    docker-php-ext-install gd mysqli pdo pdo_mysql opcache; 
COPY phpconf.ini /usr/local/etc/php/conf.d/

EXPOSE 9071