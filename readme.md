# Docker Container for dev

![License MIT](https://img.shields.io/badge/license-MIT-blue.svg?style=flat)
[![Average time to resolve an issue](http://isitmaintained.com/badge/resolution/khutran/thai-thuctap.svg)](http://isitmaintained.com/project/khutran/thai-thuctap "Average time to resolve an issue")
[![Percentage of issues still open](http://isitmaintained.com/badge/open/khutran/thai-thuctap.svg)](http://isitmaintained.com/project/khutran/thai-thuctap "Percentage of issues still open")


### WEBSERVER (HTTPD 2.4)  WEBSERVER (NGINX) + PHP-FPM + MYSQL + Mautic


## NGINX 
    IP 172.22.0.4
    //Nhận request từ client vào và reserve qua httpd xử lý website
    
##  HTTPD: 
    IP 172.22.0.5

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

### IP table:
####    172.22.0.4      hrautomail.vicoders.com
####    172.22.0.4      viwebsite.vicoders.com
####    172.22.0.4      myadmin.co

