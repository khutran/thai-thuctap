FROM mysql


RUN apt-get update && apt-get install --no-install-recommends -y \
    vim wget


#ADD DB
# ADD 104_forest_storex_db.sql /root/104_forest_storex_db.sql
# ADD mysql.sql /root/mysql.sql
# ADD initdb.sh /root/initdb.sh
# RUN chmod +x /root/initdb.sh  && service mysql start && ./root/initdb.sh

RUN chown -R mysql:mysql /var/lib/mysql/
# RUN chmod -R 755 /var/lib/mysql/ && chown -R mysql:mysql /var/lib/mysql/*

# VOLUME ["/etc/mysql", "/var/lib/mysql"]
EXPOSE 3306
