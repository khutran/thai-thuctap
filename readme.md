# Docker Container for dev


### WEBSERVER (HTTPD 2.4)  WEBSERVER (NGINX) + PHP-FPM + MYSQL + Mautic


## NGINX 
    IP 172.22.0.4
    //Nhận request từ client vào và reserve qua httpd xử lý website
    //Nhận request từ client vào và reserve qua mautic server xử lý mautic 

##  HTTPD: 
    IP 172.22.0.5
    Creat VHost:
        - wpdocker.co
        - wp1.co
        - myadmin.co
    - /opt/rh/httpd24/root/etc/httpd/conf/httpd.conf fixed PHP-FPM 172.22.0.2

##  MAUTIC:
    IP 172.22.0.7
    Creat VHost: /etc/apache2/sites-available
        - hrautomail.vicoders.com   | DocumentRoot /var/www/mautic/hrautomail.vicoders.com/
        - viwebsite.vicoders.com    | DocumentRoot /var/www/mautic/viwebsite.vicoders.com/

## PHPFPM 
    IP 172.22.0.2
    // xử lý PHP

## MYSQL 
    IP 172.22.0.3
    // Create admin user:
    

## PHPMYADMIN (website hosted HTTPD)
    IP 172.22.0.4
    DB: - mauticdb
        - hrautomail
        - viwebsite
        - wordpress

ISSUES: khi request IP 172.22.0.4 (nginx) thì ra trang wp1.co


### IP table:
####    172.22.0.4      wpdocker.co
####    172.22.0.4      wp1.co
####    172.22.0.4      hrautomail.vicoders.com
####    172.22.0.4      viwebsite.vicoders.com
####    172.22.0.4      myadmin.co

