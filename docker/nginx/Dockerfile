FROM centos:6
LABEL maintainer="thaild <thaiattt@gmail.com>"
RUN  yum -y install yum-plugin-priorities pcre-devel autoconf libtool epel-release centos-release-scl-rh centos-release-scl apr-util gcc wget

## NGINX
RUN yum install -y nginx
    
CMD ["nginx", "-g", "daemon off;"]


