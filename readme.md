# SYSTEM DOCKER CONTAINER #VMMS CLOUD

## WEBSERVER (HTTPD 2.4) + WEBSERVER (NGINX) + PHP-FPM + MYSQL + MAUTIC

### NGINX

    IP 172.22.0.4:80/443
    //Nhận request từ client và reserve qua httpd -> PHP-FPM 

### HTTPD

    IP 172.22.0.5:8080
    Creat VHost & config here:
        - wpdocker.co
        - wp1.co
        - myadmin.co
        - hrautomail.vicoders.com   | DocumentRoot /var/www/hrautomail.vicoders.com/
        - viwebsite.vicoders.com    | DocumentRoot /var/www/viwebsite.vicoders.com/
        - ...
    - /etc/httpd/conf/httpd.conf
    - /etc/httpd/conf/extra/vhosts/*.conf
        
### PHPFPM 

    IP 172.22.0.2:9000
    // file *.PHP

### MYSQL

    IP 172.22.0.3:3306
    // Create admin user:
    

### IP table:

####    172.22.0.4      wpdocker.co

####    172.22.0.4      myadmin.co

####    172.22.0.4      wp1.co

####    172.22.0.4      hrautomail.vicoders.com

####    172.22.0.4      viwebsite.vicoders.com


