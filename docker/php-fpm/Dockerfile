# https://hub.docker.com/_/php/

FROM php:7.0-fpm
LABEL maintainer="thaild <thaiattt@gmail.com>"

WORKDIR /var/www
# install the PHP extensions we need
RUN set -ex; \
    apt-get -y update; \
    apt-get install -y    libjpeg-dev libpng-dev ;\
    docker-php-ext-configure gd --with-png-dir=/usr --with-jpeg-dir=/usr; \
    docker-php-ext-install gd mysqli pdo pdo_mysql opcache; 

# bcmath bz2 calendar ctype curl dba dom enchant exif fileinfo filter ftp gd gettext gmp hash iconv imap interbase intl json ldap mbstring mcrypt mysqli oci8 odbc opcache pcntl pdo pdo_dblib pdo_firebird pdo_mysql pdo_oci pdo_odbc pdo_pgsql pdo_sqlite pgsql phar posix pspell readline recode reflection session shmop simplexml snmp soap sockets spl standard sysvmsg sysvsemsysvshm tidy tokenizer wddx xml xmlreader xmlrpc xmlwriter xsl zip

# # set recommended PHP.ini settings
# # see https://secure.php.net/manual/en/opcache.installation.php
# RUN { \
#     echo 'opcache.memory_consumption=128'; \
#     echo 'opcache.interned_strings_buffer=8'; \
#     echo 'opcache.max_accelerated_files=4000'; \
#     echo 'opcache.revalidate_freq=2'; \
#     echo 'opcache.fast_shutdown=1'; \
#     echo 'opcache.enable_cli=1'; \
#     } > /usr/local/etc/php/conf.d/opcache-recommended.ini
