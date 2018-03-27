#!/bin/sh
# chown -R mysql:mysql /var/lib/mysql
# mysql_install_db --user=mysql > /dev/null

# mysqld_safe --user=mysql &
DB="104_forest_storex_db"
USER="thaild"
PASS="thaild"

# mysql -uroot -proot -D mysql < /root/mysql.sql

# mysql -uroot -proot -e "GRANT ALL PRIVILEGES ON *.* TO '$USER'@'%' WITH GRANT OPTION;"
# mysql -uroot -proot -e "FLUSH PRIVILEGES;"

# mysql -uroot -proot -e "CREATE DATABASE $DB CHARACTER SET utf8 COLLATE utf8_general_ci";
mysql -uroot -proot -e "CREATE DATABASE $DB;"
mysql -uroot -proot -D $DB < /root/104_forest_storex_db.sql