FROM centos:6
LABEL maintainer="thaild <thaiattt@gmail.com>"
RUN yum update -y && yum -y install yum-plugin-priorities \
    pcre-devel autoconf libtool epel-release \
    centos-release-scl-rh centos-release-scl \
    apr-util gcc wget make pkgconf re2c

#Set Repository
COPY php56.repo /etc/yum.repos.d/php56.repo

#Install php
RUN yum install php-fpm php php-devel php-common php-pecl-apc php-cli php-pear -y --enablerepo=remi-php56; \
    yum install  gd-last php-pdo php-mysqlnd php-pgsql php-mysql php-gd php-mbstring php-mcrypt php-xml php-soap php-apc -y --enablerepo=remi-php56 ;\
    yum groupinstall "PHP Support" --enablerepo=remi-php56 -y; \
    yum -y clean all ;

#SETTING PHP max_execution_time/ max_input_time
COPY phpconf.ini etc/php.d/phpconf.ini

WORKDIR /var/www
EXPOSE 9010
CMD ["php-fpm", "-R", "-O", "-F"]

# CMD ["service", "php-fpm", "start"]