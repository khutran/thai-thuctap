FROM centos:6
MAINTAINER thaild <thaild@vicoders.com> (@thaild)

RUN yum -y install yum-plugin-priorities pcre-devel autoconf libtool epel-release centos-release-scl-rh centos-release-scl apr-util gcc wget vim

#httpd 2.4
RUN cd /etc/yum.repos.d/ && wget http://repos.fedorapeople.org/repos/jkaluza/httpd24/epel-httpd24.repo \
    && yum install -y httpd24.x86_64 

VOLUME /var/www

# Enable Apache Rewrite Module
# RUN a2enmod rewrite
RUN mkdir -p /var/www
RUN chown -R 755 /var/www

RUN sed -i "s/:100:101:apache:/:$APACHE_UID:$APACHE_GID:apache:/g" /etc/passwd \
    && sed -i "s/:101:apache/:$APACHE_GID:apache/g" /etc/group \
    && chown -R apache:apache /var/www
CMD ["/opt/rh/httpd24/root/usr/sbin/httpd", "-D", "FOREGROUND"]