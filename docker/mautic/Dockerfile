FROM php:7.0-apache
MAINTAINER thaild <thaild@vicoders.com> (@thaild)

# Install PHP extensions
RUN apt-get update && apt-get install --no-install-recommends -y \
    cron \
    libc-client-dev \
    libicu-dev \
    libkrb5-dev \
    libmcrypt-dev \
    libssl-dev \
    unzip \
    zip \
    vim \
    && rm /etc/cron.daily/*
RUN docker-php-ext-configure imap --with-imap --with-imap-ssl --with-kerberos \
    && docker-php-ext-install imap intl mbstring mcrypt mysqli pdo pdo_mysql zip

VOLUME /var/www

# Enable Apache Rewrite Module
RUN a2enmod rewrite
COPY ./sites-available /etc/apache2/sites-available
COPY run.sh run.sh
RUN chmod +x run.sh && ./run.sh
# RUN cd /etc/apache2/sites-available && a2ensite hrautomail.vicoders.com.conf viwebsite.vicoders.com.conf
# RUN chown -R www-data:www-data /var/www/mautic

# ENTRYPOINT ["/entrypoint.sh"]
CMD ["apache2-foreground"]
