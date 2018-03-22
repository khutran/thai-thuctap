FROM centos:6
LABEL maintainer="thaild <thaiattt@gmail.com>"
RUN  yum -y install yum-plugin-priorities pcre-devel autoconf libtool epel-release centos-release-scl-rh centos-release-scl apr-util gcc wget

## NGINX
RUN yum install -y nginx

RUN mkdir -p /var/www
# RUN chown -R 755 /var/www

# WORKDIR /var/www/html
# RUN groupadd apache && useradd -d /var/www -g apache apache


# RUN sed -i "s/:100:101:apache:/:$APACHE_UID:$APACHE_GID:apache:/g" /etc/passwd \
#     && sed -i "s/:101:apache/:$APACHE_GID:apache/g" /etc/group \
#     && chown -R apache:apache /var/www
    
CMD ["nginx", "-g", "daemon off;"]


