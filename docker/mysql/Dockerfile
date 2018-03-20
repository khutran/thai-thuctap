FROM mysql

RUN chown -R mysql:root /var/lib/mysql/
RUN apt-get update && apt-get install --no-install-recommends -y \
    vim wget

#Create admin user

# ADD mariadb.sql /root/mariadb.sql
# ADD mariadb.sh /root/mariadb.sh
# RUN chmod -R 755 /var/lib/mysql/ && chown -R mysql:mysql /var/lib/mysql/*
# RUN yum clean all
# RUN chmod +x /root/mariadb.sh 
#RUN ./root/mariadb.sh
# Add VOLUMEs to allow backup of config and databases
VOLUME ["/etc/mysql", "/var/lib/mysql"]
EXPOSE 3306
