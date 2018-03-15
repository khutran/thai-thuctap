FROM mysql
RUN chown -R mysql:root /var/lib/mysql/

ENV MYSQL_ROOT_PASSWORD=root
ENV MYSQL_USER=thaild
ENV MYSQL_PASSWORD=thaild
ENV MYSQL_DATABASE=wordpress
ENV MYSQL_ROOT_HOST=0.0.0.0

ENV MYSQL_DATA_DIR=/var/lib/mysql \
    MYSQL_RUN_DIR=/run/mysqld \
    MYSQL_LOG_DIR=/var/log/mysql

COPY docker-entrypoint.sh /usr/local/bin/

ENTRYPOINT ["docker-entrypoint.sh"]

EXPOSE 3306
CMD ["mysqld"]