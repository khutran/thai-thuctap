# OVERVIEW SYSTEM 'Docker Container for dev'

![License MIT](https://img.shields.io/badge/license-MIT-blue.svg?style=flat)
[![Average time to resolve an issue](http://isitmaintained.com/badge/resolution/khutran/thai-thuctap.svg)](http://isitmaintained.com/project/khutran/thai-thuctap "Average time to resolve an issue")
[![Percentage of issues still open](http://isitmaintained.com/badge/open/khutran/thai-thuctap.svg)](http://isitmaintained.com/project/khutran/thai-thuctap "Percentage of issues still open")



Exposed port and volumes
----

The image exposes ports `8080`, `3306`, `80`, `9000`, ``and exports four volumes:

* `/logs/httpd`, containing HTTPD log files.
* `/logs/mysql` containing MYSQL log files.
* `/logs/nginx` containing NGINX log files.
* `/etc/nginx`, where NGINX data files are stores.
* `/etc/nginx/conf.d`, where NGINX data configure [DOMAIN-NAME] are stores.
* `/etc/httpd`, where HTTPD data files are stores.
* `/etc/httpd/extra/vhosts`, where HTTPD configure virtual-hosts are stores.
* `/etc/php-fpm.d/www.conf`, where PHP-FPM data files configure are stores.
* `/www`, used as Apache's [DocumentRoot directory](http://httpd.apache.org/docs/2.4/en/mod/core.html#documentroot).


The user and group owner id for the DocumentRoot directory `/var/www/html` are both 33 (`uid=33(www-data) gid=33(www-data) groups=33(www-data)`).

The user and group owner id for the MariaDB directory `/var/log/mysql` are 105 and 108 repectively (`uid=105(mysql) gid=108(mysql) groups=108(mysql)`).

Use cases
----


DOMAIN-NAME
----
    - hrautomail.vicoders.com
    - viwebsite.vicoders.com
    - ...


## How to use it

    git clone https://github.com/khutran/thai-thuctap.git -b branch_name
    cd thai-thuctap
    docker-compose up -d
    
After a few seconds your environment is ready and you can access it at http://[DOMAIN-NAME]

## Credentials

MYSQL:

* User: thaild
* Password: thaild
* Database: 
* Hostname: 172.22.0.3:3306


MAUTIC website default password:

* User: mautic
* Password: mautic