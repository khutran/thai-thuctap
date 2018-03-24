FROM centos:6
LABEL maintainer="thaild <thaiattt@gmail.com>"
RUN  yum -y install \
    yum-plugin-priorities \
    pcre-devel \
    autoconf \
    openssl-devel \
    libtool \
    epel-release \
    make centos-release-scl \
    apr-devel \
    apr-util-devel \
    gcc \
    wget \
    expat-devel \
    pcre pcre-devel \
    vim 
RUN yum groupinstall "Development Tools" -y
# #httpd 2.4
RUN cd /usr/local/src/ && wget http://www-eu.apache.org/dist//httpd/httpd-2.4.29.tar.gz \
    && tar xvfz httpd-2.4.29.tar.gz && cd httpd-2.4.29 
RUN cd /usr/local/src/httpd-2.4.29 && wget http://mirrors.viethosting.com/apache//apr/apr-1.6.3.tar.gz && \
    tar xvfz apr-1.6.3.tar.gz &&\
    cd apr-1.6.3 && ./configure  --prefix=/usr/local/src/httpd-2.4.29/srclib/apr &&\
    make && make install 
RUN    cd /usr/local/src/httpd-2.4.29 && wget http://mirrors.viethosting.com/apache//apr/apr-util-1.6.1.tar.gz && \
    tar xvfz apr-util-1.6.1.tar.gz &&\
    cd apr-util-1.6.1 && ./configure --prefix=/usr/local/src/httpd-2.4.29/srclib/apr-util --with-apr=/usr/local/src/httpd-2.4.29/srclib/apr &&\
    make && make install


RUN cd /usr/local/src/httpd-2.4.29 && mkdir -p /etc/httpd/ && \
    ./configure --prefix=/etc/httpd/ --sbindir=/usr/sbin/ --enable-ssl --enable-so  --with-apr=/usr/local/src/httpd-2.4.29/srclib/apr --with-apr-util=/usr/local/src/httpd-2.4.29/srclib/apr-util && \
    make &&    make install

CMD ["/usr/sbin/httpd", "-D", "FOREGROUND"]

