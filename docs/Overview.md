# OVERVIEW SYSTEM 'VMMS Docker Container'


![Containers](https://github.com/khutran/thai-thuctap/blob/develop/docs/vmms_cloud.jpg)

Exposed port and volumes
----

The image exposes ports `8080`, `3306`, `80`, `9000`, ``and exports four volumes:

* `/logs/httpd`, containing HTTPD log files.
* `/logs/mysql` containing MYSQL log files.
* `/logs/nginx` containing NGINX log files.
* `/etc/nginx`, where NGINX data files are stores.
* `/etc/httpd`, where HTTPD data files are stores.
* `/etc/php-fpm.d/www.conf`, where PHP-FPM data files configure are stores.
* `/www`, used as Apache's [DocumentRoot directory](http://httpd.apache.org/docs/2.4/en/mod/core.html#documentroot).


The user and group owner id for the DocumentRoot directory `/var/www/html` are both 33 (`uid=33(www-data) gid=33(www-data) groups=33(www-data)`).

The user and group owner id for the MariaDB directory `/var/log/mysql` are 105 and 108 repectively (`uid=105(mysql) gid=108(mysql) groups=108(mysql)`).

Use cases
----
### ADD WEBSITE
    - COPY source code to folder ~dir/wwww/
    - name-folder = [DOMAIN-NAME]

DOMAIN-NAME
----
    - hrautomail.vicoders.com
    - viwebsite.vicoders.com
    - ...


How to use it
----

    git clone https://github.com/khutran/thai-thuctap.git -b branch_name
    cd thai-thuctap
    docker-compose up -d
    
After a few seconds your environment is ready and you can access it at http://[DOMAIN-NAME]


Credentials
----

MYSQL:

* User: thaild
* Password: thaild
* Database: 
* Hostname: 172.22.0.3:3306


MAUTIC website default password:

* User: mautic
* Password: mautic


### Notes

If you experience trouble with containers, you can remove them all and start from scratch with the following commands:
```
docker stop $(docker ps -a -q)
 
docker rm $(docker ps -a -q)
```

Or you can re-build your containers with `docker-compose up` again