# Docker Container for dev
### WEBSERVER (HTTPD 2.4)  WEBSERVER (NGINX) + PHP-FPM + MYSQL

## NGINX
    IP 172.22.0.4

##  HTTPD: 
    IP 172.22.0.5
    Creat VHost:
        - wpdocker.co
        - wp1.co
    - /opt/rh/httpd24/root/etc/httpd/conf/httpd.conf fixed PHP-FPM 172.22.0.2
## PHPFPM 
    IP 172.22.0.2

## MYSQL 
    IP 172.22.0.3

## PHPMYADMIN 172.22.0.6
ISSUE: khi truy vấn thẳng vào IP 172.22.0.4 (nginx) thì ra trang wp1.co
